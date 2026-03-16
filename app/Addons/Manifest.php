<?php

namespace App\Store\Addons;

use Illuminate\Foundation\PackageManifest;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use ReflectionClass;

/**
 * This manifest was heavily inspired by Statamic..
 */
class Manifest extends PackageManifest
{
    /**
     * Build the addon manifest.
     *
     * @return void
     */
    public function build()
    {
        $this->manifest = null;

        $packages = [];

        if ($this->files->exists($path = $this->vendorPath.'/composer/installed.json')) {
            $installed = json_decode($this->files->get($path), true);
            $packages = $installed['packages'] ?? $installed;
        }

        $this->write(collect($packages)->filter(function ($package) {
            return Arr::has($package, 'extra.store');
        })->keyBy('name')->map(function ($package) {
            return $this->formatPackage($package);
        })->filter()->all());

        $this->getManifest();
    }

    /**
     * Format a given composer package into our addon format.
     *
     * @param  array  $package
     * @return void|array
     */
    protected function formatPackage($package)
    {
        if (! $provider = $package['extra']['laravel']['providers'][0] ?? null) {
            return;
        }

        $reflector = new ReflectionClass($provider);
        $providerParts = explode('\\', $provider, -1);
        $namespace = implode('\\', $providerParts);
        $autoload = $package['autoload']['psr-4'][$namespace.'\\'];

        $directory = Str::remove(rtrim($autoload, '/'), dirname($reflector->getFileName()));
        $json = json_decode(File::get($directory.'composer.json'), true);

        $store = $json['extra']['store'] ?? [];
        $author = $json['authors'][0] ?? null;

        $config = config('store.addons.'.$package['name'], [
            'license' => null,
        ]);

        return [
            'id' => base64_encode($package['name']),
            'slug' => $store['slug'] ?? null,
            'editions' => $store['editions'] ?? [],
            'github_url' => $package['source']['url'] ?? '',
            'version' => $package['version'],
            'namespace' => $namespace,
            'autoload' => $autoload,
            'provider' => $provider,
            'name' => $store['name'] ?? Arr::last($providerParts),
            'author' => $author['name'] ?? null,
            'email' => $package['support']['email'] ?? null,
        ];
    }

    /**
     * Get a collection of our addons.
     */
    public function addons(): Collection
    {
        return collect($this->getManifest());
    }
}
