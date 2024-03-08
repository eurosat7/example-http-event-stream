<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\TestCase;

// please run from inside docker.
// > make docker-phpunit

final class ToDoTest extends TestCase
{
    /**
     * @throws AssertionFailedError
     */
    public function testToDo(): void
    {
        ToDoTest::assertTrue(true);
        // ToDoTest::fail('t o d o');
    }
}
