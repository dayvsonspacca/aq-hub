<?php

$finder = (new PhpCsFixer\Finder())
    ->in(['src', 'tests']);

return (new PhpCsFixer\Config())
    ->setRules([
        '@PSR12' => true,
        'strict_param' => true,
        'declare_strict_types' => true,
        'single_quote' => true,
        'binary_operator_spaces' => [
            'default' => 'single_space',
            'operators' => ['=' => 'align'],
        ],
        'no_unused_imports' => true,
        'ordered_imports' => [
            'sort_algorithm' => 'alpha',
        ],
        'group_imports' => ['group_classes' => true, 'group_functions' => true, 'group_constants' => true],
    ])
    ->setFinder($finder);
