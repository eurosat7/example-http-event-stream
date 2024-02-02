<?php
declare(strict_types=1);

$finder = (new PhpCsFixer\Finder());

/** @phpstan-ignore-next-line */
$finder->in(__DIR__);
$finder->exclude('bin');
$finder->exclude('dist');
$finder->exclude('vendor');

return (new PhpCsFixer\Config())
    ->setRules([
        '@Symfony' => true,
    ])
    ->setFinder($finder);
