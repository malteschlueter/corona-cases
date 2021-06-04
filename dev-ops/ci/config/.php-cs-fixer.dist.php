<?php

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$projectDir = dirname(__DIR__, 3);

$finder = Finder::create()
    ->in([
        $projectDir . '/config',
        $projectDir . '/src',
        $projectDir . '/tests',
    ])
    ->exclude([
        'var',
    ])
;

return (new Config())
    ->setFinder($finder)
    ->setCacheFile(dirname(__DIR__) . '/cache/.php-cs-fixer.cache')
    ->setRiskyAllowed(true)
    ->setRules([
        '@Symfony' => true,
        '@Symfony:risky' => true,
        '@DoctrineAnnotation' => true,
        '@PHP80Migration' => true,
        '@PHP80Migration:risky' => true,
        '@PHPUnit84Migration:risky' => true,

        'array_indentation' => true,
        'array_syntax' => ['syntax' => 'short'],
        'compact_nullable_typehint' => true,
        'concat_space' => ['spacing' => 'one'],
        'method_chaining_indentation' => true,
        'multiline_whitespace_before_semicolons' => ['strategy' => 'new_line_for_chained_calls'],
        'no_useless_else' => true,
        'no_useless_return' => true,
        'ordered_imports' => ['imports_order' => ['class', 'function', 'const']],
        'phpdoc_order' => true,
        'visibility_required' => ['elements' => ['property', 'method', 'const']],
    ])
;
