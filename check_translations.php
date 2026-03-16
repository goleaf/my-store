<?php

$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator('app'));
$keys = [];

foreach ($files as $file) {
    if ($file->isDir()) continue;
    if ($file->getExtension() !== 'php') continue;

    $content = file_get_contents($file->getPathname());
    if (preg_match_all('/__\([\'"](admin|store)::(.*?)[\'"]\)/', $content, $matches)) {
        foreach ($matches[2] as $key) {
            $keys[$matches[1][0]][] = $key;
        }
    }
}

foreach ($keys as $namespace => $nsKeys) {
    $nsKeys = array_unique($nsKeys);
    foreach ($nsKeys as $key) {
        if (str_contains($key, '$')) continue; // Skip dynamic keys for now

        $fullKey = "$namespace::$key";
        if (__($fullKey) === $fullKey) {
            echo "MISSING KEY: $fullKey\n";
        }
    }
}
