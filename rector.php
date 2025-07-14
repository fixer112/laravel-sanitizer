<?php

use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\Rector\Property\TypedPropertyFromStrictConstructorRector;

return RectorConfig::configure()

    ->withPaths([
        __DIR__.'/src',
    ])
    // register single rule
    ->withRules([
        TypedPropertyFromStrictConstructorRector::class,
    ])
    ->withSkip([
        __DIR__.'/vendor',
    ])
    // here we can define, what prepared sets of rules will be applied
    ->withPreparedSets(
        deadCode: true,
        codeQuality: true,
        privatization: true,
        earlyReturn: true,
        strictBooleans: true,
    )->withPhpSets();
