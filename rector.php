<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Doctrine\Set\DoctrineSetList;
use Rector\Symfony\Set\SymfonySetList;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/config',
        __DIR__ . '/public',
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ])
//    ->withSymfonyContainerXml(__DIR__ . '/var/cache/dev/App_KernelDevDebugContainer.xml')
    ->withSets([
        DoctrineSetList::ANNOTATIONS_TO_ATTRIBUTES,
        SymfonySetList::SYMFONY_71,
        SymfonySetList::ANNOTATIONS_TO_ATTRIBUTES,
        SymfonySetList::SYMFONY_CODE_QUALITY,
        SymfonySetList::SYMFONY_CONSTRUCTOR_INJECTION,
    ])
    // uncomment to reach your current PHP version
    ->withPhpSets()
    ->withTypeCoverageLevel(0);
