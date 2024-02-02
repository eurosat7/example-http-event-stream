<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Class_\InlineConstructorDefaultToPropertyRector;
use Rector\Config\RectorConfig;
use Rector\Core\ValueObject\PhpVersion;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Set\ValueObject\SetList;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->paths([
        __DIR__ . '/src',
    ]);
    $rectorConfig->phpVersion(PhpVersion::PHP_82); // @todo PHP_83 not yet supported
    $rectorConfig->rule(
        InlineConstructorDefaultToPropertyRector::class
    );

    $rectorConfig->sets([
        LevelSetList::UP_TO_PHP_82, // @todo UP_TO_PHP_83 not yet supported
        SetList::DEAD_CODE,
        SetList::CODE_QUALITY,
        SetList::EARLY_RETURN,
        SetList::INSTANCEOF,
    ]);
};
