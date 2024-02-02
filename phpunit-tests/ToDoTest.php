<?php
// please run from inside docker.
// > make docker-phpunit

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\TestCase;

include(dirname(__DIR__) . '/vendor/autoload.php');

final class ToDoTest extends TestCase
{
    /**
     * @throws AssertionFailedError
     */
    public function testToDo(): void
    {
        ToDoTest::fail('todo');
    }
}
