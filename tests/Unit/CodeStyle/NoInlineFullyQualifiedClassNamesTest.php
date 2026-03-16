<?php

use PhpParser\ErrorHandler\Throwing;
use PhpParser\Node;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor\NameResolver;
use PhpParser\ParserFactory;
use PhpParser\Node\Stmt;

test('project php files do not use inline fully qualified class names in code', function () {
    $root = base_path();
    $directories = ['app', 'routes', 'database', 'tests'];
    $paths = [];

    foreach ($directories as $directory) {
        $base = $root . DIRECTORY_SEPARATOR . $directory;

        if (! is_dir($base)) {
            continue;
        }

        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($base, FilesystemIterator::SKIP_DOTS)
        );

        foreach ($iterator as $file) {
            if ($file->isFile() && $file->getExtension() === 'php') {
                $paths[] = $file->getPathname();
            }
        }
    }

    sort($paths);

    $parser = (new ParserFactory())->createForNewestSupportedVersion();
    $violations = [];

    foreach ($paths as $path) {
        $code = file_get_contents($path);

        try {
            $ast = $parser->parse($code, new Throwing());

            $resolver = new NodeTraverser();
            $resolver->addVisitor(new NameResolver(null, ['preserveOriginalNames' => true, 'replaceNodes' => false]));
            $ast = $resolver->traverse($ast);
        } catch (Throwable $throwable) {
            $violations[] = sprintf(
                '%s parse error: %s',
                str_replace($root . DIRECTORY_SEPARATOR, '', $path),
                $throwable->getMessage(),
            );

            continue;
        }

        $walk = function (array $nodes, ?Node $parent = null) use (&$walk, &$violations, $path, $root): void {
            foreach ($nodes as $node) {
                if (! $node instanceof Node) {
                    continue;
                }

                if ($node instanceof Node\Name\FullyQualified && isClassLikeNameContext($node, $parent)) {
                    $violations[] = sprintf(
                        '%s:%d %s',
                        str_replace($root . DIRECTORY_SEPARATOR, '', $path),
                        $node->getStartLine(),
                        $node->toString(),
                    );
                }

                foreach ($node->getSubNodeNames() as $subNodeName) {
                    $value = $node->$subNodeName;

                    if (is_array($value)) {
                        $walk($value, $node);
                    } elseif ($value instanceof Node) {
                        $walk([$value], $node);
                    }
                }
            }
        };

        $walk(getTopLevelStatements($ast));
    }

    expect($violations)->toBeEmpty(implode(PHP_EOL, $violations));
});

function getTopLevelStatements(array $ast): array
{
    return count($ast) === 1 && $ast[0] instanceof Stmt\Namespace_
        ? $ast[0]->stmts
        : $ast;
}

function isClassLikeNameContext(Node $node, ?Node $parent): bool
{
    if ($parent === null) {
        return false;
    }

    return match (true) {
        $parent instanceof Node\Param,
        $parent instanceof Node\NullableType,
        $parent instanceof Node\UnionType,
        $parent instanceof Node\IntersectionType,
        $parent instanceof Node\Stmt\Class_,
        $parent instanceof Node\Stmt\Interface_,
        $parent instanceof Node\Stmt\TraitUse,
        $parent instanceof Node\Stmt\Catch_,
        $parent instanceof Node\Stmt\TraitUseAdaptation\Alias,
        $parent instanceof Node\Stmt\TraitUseAdaptation\Precedence,
        $parent instanceof Node\Stmt\Property,
        $parent instanceof Node\Expr\New_,
        $parent instanceof Node\Expr\Instanceof_,
        $parent instanceof Node\Expr\StaticCall,
        $parent instanceof Node\Expr\ClassConstFetch,
        $parent instanceof Node\Expr\StaticPropertyFetch,
        $parent instanceof Node\Attribute,
        $parent instanceof Node\Stmt\ClassMethod,
        $parent instanceof Node\Stmt\Function_,
        $parent instanceof Node\Expr\Closure,
        $parent instanceof Node\Expr\ArrowFunction,
        $parent instanceof Node\Stmt\Enum_ => true,
        default => false,
    };
}
