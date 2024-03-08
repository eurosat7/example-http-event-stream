<?php

declare(strict_types=1);

namespace App\Service;

use LogicException;
use Symfony\Component\HttpFoundation\Exception\SessionNotFoundException;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

readonly class SessionService
{
    public function __construct(
        private RequestStack $requestStack,
    ) {
    }

    /**
     * @param array<int, string> $keypath
     *
     * x-throws LogicException
     */
    public function getSessionValue(array $keypath): mixed
    {
        try {
            $key = implode('-', $keypath);

            return $this->getSession()->get($key) ?? null;
        } catch (LogicException) {
            throw new LogicException('could not read from session');
        }
    }

    /**
     * @param array<int, string> $keypath
     *
     * @throws LogicException
     */
    public function updateSessionValue(array $keypath, mixed $value): void
    {
        try {
            $key = implode('-', $keypath);
            $this->getSession()->set($key, $value);
        } catch (LogicException $e) {
            throw new LogicException('could not write to session', $e->getCode(), $e);
        }
    }

    /**
     * @throws LogicException
     */
    private function getSession(): SessionInterface
    {
        try {
            return $this->requestStack->getSession();
        } catch (SessionNotFoundException) {
            throw new LogicException('no valid session found');
        }
    }
}
