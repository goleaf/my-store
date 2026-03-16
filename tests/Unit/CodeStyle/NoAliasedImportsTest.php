<?php

test('project php files do not use aliased imports', function () {
    $root = base_path();
    $excludedDirectories = [
        $root . DIRECTORY_SEPARATOR . 'vendor',
        $root . DIRECTORY_SEPARATOR . 'storage',
        $root . DIRECTORY_SEPARATOR . 'bootstrap' . DIRECTORY_SEPARATOR . 'cache',
        $root . DIRECTORY_SEPARATOR . 'node_modules',
    ];

    $iterator = new RecursiveIteratorIterator(
        new RecursiveCallbackFilterIterator(
            new RecursiveDirectoryIterator($root, FilesystemIterator::SKIP_DOTS),
            function (SplFileInfo $file, string $path) use ($excludedDirectories): bool {
                if (! $file->isDir()) {
                    return true;
                }

                foreach ($excludedDirectories as $excludedDirectory) {
                    if ($file->getPathname() === $excludedDirectory) {
                        return false;
                    }
                }

                return true;
            }
        )
    );

    $violations = [];

    foreach ($iterator as $file) {
        if (! $file->isFile() || $file->getExtension() !== 'php') {
            continue;
        }

        $content = file_get_contents($file->getPathname());

        if (! preg_match_all('/^use\\s+.+\\s+as\\s+.+;$/m', $content, $matches, PREG_OFFSET_CAPTURE)) {
            continue;
        }

        foreach ($matches[0] as [$statement, $offset]) {
            $line = substr_count(substr($content, 0, $offset), PHP_EOL) + 1;

            $violations[] = sprintf(
                '%s:%d %s',
                str_replace($root . DIRECTORY_SEPARATOR, '', $file->getPathname()),
                $line,
                trim($statement),
            );
        }
    }

    expect($violations)->toBeEmpty(implode(PHP_EOL, $violations));
});
