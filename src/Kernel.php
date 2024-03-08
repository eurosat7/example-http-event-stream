<?php

declare(strict_types=1);

namespace App;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

/**
 * @phpstan-ignore-next-line
 */
class Kernel extends BaseKernel
{
    use MicroKernelTrait;
}
