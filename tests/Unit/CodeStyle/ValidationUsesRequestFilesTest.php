<?php

test('project php files keep validation rules inside request classes', function () {
    $root = app_path();
    $allowedValidatorMakeFiles = [
        app_path('Http/Requests/BaseRequest.php'),
    ];
    $requestBackedResourceFiles = [
        'app/Filament/Resources/CurrencyResource.php',
        'app/Filament/Resources/CustomerResource.php',
        'app/Filament/Resources/DeliveryZoneResource.php',
    ];

    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($root, FilesystemIterator::SKIP_DOTS)
    );

    $violations = [];

    foreach ($iterator as $file) {
        if (! $file->isFile() || $file->getExtension() !== 'php') {
            continue;
        }

        $path = $file->getPathname();
        $relativePath = str_replace(base_path() . DIRECTORY_SEPARATOR, '', $path);
        $content = file_get_contents($path);

        if (! str_starts_with($relativePath, 'app/Http/Requests/')) {
            collect([
                '/\\$this->validate\\s*\\(\\s*\\[/',
                '/->rules\\s*\\(\\s*\\[/',
                '/->rule\\s*\\(/',
            ])->each(function (string $pattern) use (&$violations, $content, $relativePath): void {
                if (! preg_match_all($pattern, $content, $matches, PREG_OFFSET_CAPTURE)) {
                    return;
                }

                foreach ($matches[0] as [$statement, $offset]) {
                    $line = substr_count(substr($content, 0, $offset), PHP_EOL) + 1;

                    $violations[] = sprintf(
                        '%s:%d %s',
                        $relativePath,
                        $line,
                        trim($statement),
                    );
                }
            });
        }

        if (
            str_starts_with($relativePath, 'app/Filament/Resources/')
            && str_contains($relativePath, '/Schemas/')
        ) {
            collect([
                '/->required\\s*\\(\\s*\\)/',
                '/->email\\s*\\(/',
                '/->url\\s*\\(/',
                '/->numeric\\s*\\(\\s*\\)/',
                '/->integer\\s*\\(\\s*\\)/',
                '/->unique\\s*\\(/',
                '/->image\\s*\\(/',
                '/->tel\\s*\\(/',
                '/->minValue\\s*\\(/',
                '/->maxValue\\s*\\(/',
            ])->each(function (string $pattern) use (&$violations, $content, $relativePath): void {
                if (! preg_match_all($pattern, $content, $matches, PREG_OFFSET_CAPTURE)) {
                    return;
                }

                foreach ($matches[0] as [$statement, $offset]) {
                    $line = substr_count(substr($content, 0, $offset), PHP_EOL) + 1;

                    $violations[] = sprintf(
                        '%s:%d %s',
                        $relativePath,
                        $line,
                        trim($statement),
                    );
                }
            });
        }

        if (in_array($relativePath, $requestBackedResourceFiles, true)) {
            collect([
                '/->required\\s*\\(\\s*\\)/',
                '/->nullable\\s*\\(/',
                '/->email\\s*\\(/',
                '/->url\\s*\\(/',
                '/->numeric\\s*\\(\\s*\\)/',
                '/->integer\\s*\\(\\s*\\)/',
                '/->unique\\s*\\(/',
                '/->image\\s*\\(/',
                '/->tel\\s*\\(/',
                '/->minLength\\s*\\(/',
                '/->maxLength\\s*\\(/',
                '/->length\\s*\\(/',
                '/->minValue\\s*\\(/',
                '/->maxValue\\s*\\(/',
            ])->each(function (string $pattern) use (&$violations, $content, $relativePath): void {
                if (! preg_match_all($pattern, $content, $matches, PREG_OFFSET_CAPTURE)) {
                    return;
                }

                foreach ($matches[0] as [$statement, $offset]) {
                    $line = substr_count(substr($content, 0, $offset), PHP_EOL) + 1;

                    $violations[] = sprintf(
                        '%s:%d %s',
                        $relativePath,
                        $line,
                        trim($statement),
                    );
                }
            });
        }

        if (in_array($path, $allowedValidatorMakeFiles, true)) {
            continue;
        }

        if (! preg_match_all('/Validator::make\\s*\\(/', $content, $matches, PREG_OFFSET_CAPTURE)) {
            continue;
        }

        foreach ($matches[0] as [$statement, $offset]) {
            $line = substr_count(substr($content, 0, $offset), PHP_EOL) + 1;

            $violations[] = sprintf(
                '%s:%d %s',
                $relativePath,
                $line,
                trim($statement),
            );
        }
    }

    expect($violations)->toBeEmpty(implode(PHP_EOL, $violations));
});
