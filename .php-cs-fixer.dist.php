<?php

$finder = PhpCsFixer\Finder::create()
        ->notPath('./config/preload.php')
        ->notPath('./public/index.php')
        ->notPath('./tests/bootstrap.php')
        ->in(__DIR__.'/{src,tests}')
;

return (new PhpCsFixer\Config())
    ->setRules([
        '@Symfony' => true,
        '@Symfony:risky' => true,
        'array_syntax' => ['syntax' => 'short'],
        'list_syntax' => ['syntax' => 'short'],
        'method_argument_space' => ['on_multiline' => 'ensure_fully_multiline'],
        'strict_comparison' => true,
        'strict_param' => true,
        'array_syntax' => ['syntax' => 'short'],
    ])
    ->setFinder($finder)
;
