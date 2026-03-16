<?php

namespace App\Store\Base;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Spatie\StructureDiscoverer\Discover;
use Symfony\Component\Finder\Exception\DirectoryNotFoundException;

class ModelManifest implements ModelManifestInterface
{
    /**
     * The collection of models to register to this manifest.
     */
    protected array $models = [];

    /**
     * Bind initial models in container and set explicit model binding.
     */
    public function register(): void
    {
        // Discover models
        $modelClasses = Discover::in(__DIR__.'/../Models')
            ->classes()
            ->extending(BaseModel::class)
            ->get();

        foreach ($modelClasses as $modelClass) {
            $interfaceClass = $this->guessContractClass($modelClass);
            $this->models[$interfaceClass] = $modelClass;
            $this->bindModel($interfaceClass, $modelClass);
        }
    }

    /**
     * Add a directory of models.
     */
    public function addDirectory(string $dir): void
    {
        try {
            $modelClasses = Discover::in($dir)
                ->classes()
                ->extending(BaseModel::class)
                ->get();

            foreach ($modelClasses as $modelClass) {
                $interfaceClass = $this->guessContractClass($modelClass);
                $this->models[$interfaceClass] = $modelClass;
                $this->bindModel($interfaceClass, $modelClass);
            }
        } catch (DirectoryNotFoundException $e) {
            Log::error($e->getMessage());
        }
    }

    /**
     * Register models.
     */
    public function add(string $interfaceClass, string $modelClass): void
    {
        $this->validateClassIsEloquentModel($modelClass);

        $this->models[$interfaceClass] = $modelClass;

        $this->bindModel($interfaceClass, $modelClass);
    }

    /**
     * Replace a model with a different implementation.
     */
    public function replace(string $interfaceClass, string $modelClass): void
    {
        $this->add($interfaceClass, $modelClass);
    }

    /**
     * Gets the registered class for the interface.
     */
    public function get(string $interfaceClass): ?string
    {
        return $this->models[$interfaceClass] ?? null;
    }

    /**
     * Validate class is an eloquent model.
     *
     * @throws \InvalidArgumentException
     */
    private function validateClassIsEloquentModel(string $class): void
    {
        if (! is_subclass_of($class, Model::class)) {
            throw new \InvalidArgumentException(sprintf('Given [%s] is not a subclass of [%s].', $class, Model::class));
        }
    }

    protected function bindModel(string $interfaceClass, string $modelClass): void
    {
        // Bind in container
        App::bind($interfaceClass, $modelClass);

        // Route model binding
        Route::model($this->bindingName($modelClass), $modelClass);

        // Morph map
        if (App::isBooted()) {
            Relation::morphMap([
                $this->getMorphMapKey($this->findStoreModel($modelClass) ?? $modelClass) => $modelClass,
            ]);
        }
    }

    protected function bindingName(string $modelClass): string
    {
        $shortName = (new \ReflectionClass($modelClass))->getShortName();

        return Str::camel($shortName);
    }

    public function guessContractClass(string $modelClass): string
    {
        $class = new \ReflectionClass($modelClass);

        $shortName = $class->getShortName();
        $namespace = $class->getNamespaceName();

        $storeContract = collect(
            $class->getInterfaceNames()
        )->first(
            fn ($contract) => str_contains("App\\Store\\Models\\Contracts\\{$shortName}", $contract)
        );

        return $storeContract ?: "{$namespace}\\Contracts\\$shortName";
    }

    public function guessModelClass(string $modelContract): string
    {
        // Are we passing through the morph class name?
        if (
            ! class_exists($modelContract) &&
            $morphedClass = Relation::morphMap()[$modelContract] ?? null
        ) {
            return $morphedClass;
        }

        $shortName = (new \ReflectionClass($modelContract))->getShortName();

        return 'App\\Store\\Models\\'.$shortName;
    }

    public function findStoreModel(string|BaseModel $model): ?string
    {
        $class = (new \ReflectionClass($model))->getName();

        foreach (class_parents($class) as $ancestorClass) {
            if ($this->isStoreModel($ancestorClass)) {
                return $ancestorClass;
            }
        }

        return null;
    }

    public function isStoreModel(string|BaseModel $model): bool
    {
        $class = (new \ReflectionClass($model));

        return $class->getNamespaceName() == 'App\\Store\\Models';
    }

    public function morphMap(): void
    {
        $modelClasses = collect(
            Discover::in(__DIR__.'/../Models')
                ->classes()
                ->extending(BaseModel::class)
                ->get()
        )->mapWithKeys(
            fn ($class) => [
                $this->getMorphMapKey($class) => $class::modelClass(),
            ]
        );

        Relation::morphMap($modelClasses->toArray());
    }

    public function getMorphMapKey($className): string
    {
        $prefix = config('store.database.morph_prefix', null);
        $key = \Illuminate\Support\Str::snake(class_basename($className));

        return "{$prefix}{$key}";
    }
}
