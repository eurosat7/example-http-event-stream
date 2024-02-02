<?php

declare(strict_types=1);

namespace Eurosat7\ExampleHttpEventStream\Controller;

use LogicException;
use Psr\Container\ContainerExceptionInterface;
use Random\RandomException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Exception\SessionNotFoundException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Uid\UuidV6;

/**
 * @phpstan-ignore-next-line
 */
final class UploadController extends AbstractController
{
    #[Route('/upload')]
    public function indexAction(): Response
    {
        return $this->render(
            'upload/index.html.twig',
            [
                'title' => 'index - upload',
            ]
        );
    }

    #[Route('/upload/process')]
    public function processAction(): Response
    {
        $uuid = new UuidV6();
        return $this->render(
            'upload/process.html.twig',
            [
                'title' => 'process - upload',
                'ping' => $this->generateUrl('upload-status', ['id' => $uuid]),
            ]
        );
    }

    #[Route('/upload/process-background/{{ uuid }}')]
    public function processBackgroundAction(string $uuid): JsonResponse
    {
        $act = 0;
        $max = 100;
        $this->setSessionValue(['process', $uuid, 'max'], $max);
        while ($act < $max) {
            try {
                $act += random_int(1, 4);
            } catch (RandomException) {
                $act += 1;
            }
            $this->setSessionValue(['process', $uuid, 'act'], $act);
        }
        return $this->json(true);
    }

    #[Route('/upload/status/{{ uuid }}')]
    public function statusAction(string $uuid): JsonResponse
    {
        return $this->json(
            [
                'error' => ($this->getSessionValue(['process', $uuid, 'act']) !== null) ? 1 : 0,
                'act' => $this->getSessionValue(['process', $uuid, 'act']) ?? 0,
                'max' => $this->getSessionValue(['process', $uuid, 'max']) ?? 0,
            ]
        );
    }

    /**
     * @param array<int, string> $keypath
     */
    private function getSessionValue(array $keypath): mixed
    {
        try {
            $key = implode('-', $keypath);
            return $this->getSession()->get($key) ?? null;
        } catch (LogicException $e) {
            try {
                $this->addFlash('error', 'failed ' . $e::class . ': ' . $e->getMessage());
            } catch (LogicException) {
                // noop
            }
            return null;
        }
    }

    /**
     * @param array<int, string> $keypath
     */
    private function setSessionValue(array $keypath, mixed $value): void
    {
        try {
            $key = implode('-', $keypath);
            $this->getSession()->set($key, $value);
        } catch (LogicException $e) {
            try {
                $this->addFlash('error', 'failed ' . $e::class . ': ' . $e->getMessage());
            } catch (LogicException) {
                // noop
            }
        }
    }

    /**
     * @throws LogicException
     */
    private function getSession(): SessionInterface
    {
        try {
            $request = $this->getRequestStack();
            return $request->getSession();
        } catch (SessionNotFoundException $e) {
            $this->addFlash('error', 'failed ' . $e::class . ': ' . $e->getMessage());
            throw new LogicException('no valid session found', $e->getCode(), $e);
        }
    }

    private function getRequestStack(): RequestStack
    {
        try {
            /** @var RequestStack $request */
            $request = $this->container->get('request_stack');
            return $request;
        } catch (ContainerExceptionInterface) {
            return new RequestStack();
        }
    }
}
