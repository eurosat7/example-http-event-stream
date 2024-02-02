<?php

declare(strict_types=1);

namespace Eurosat7\ExampleHttpEventStream\Controller;

use Eurosat7\ExampleHttpEventStream\Service\SessionService;
use LogicException;
use Random\RandomException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
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

    /**
     * @throws LogicException
     */
    #[Route('/upload/process-background/{{ uuid }}')]
    public function processBackgroundAction(string $uuid, SessionService $sessionService): JsonResponse
    {
        $act = 0;
        $max = 100;
        $sessionService->setSessionValue(['process', $uuid, 'max'], $max);
        while ($act < $max) {
            try {
                $act += random_int(1, 4);
            } catch (RandomException) {
                $act += 1;
            }
            $sessionService->setSessionValue(['process', $uuid, 'act'], $act);
        }
        return $this->json(true);
    }

    /**
     * x-throws LogicException
     */
    #[Route('/upload/status/{{ uuid }}')]
    public function statusAction(string $uuid, SessionService $sessionService): JsonResponse
    {
        return $this->json(
            [
                'error' => ($sessionService->getSessionValue(['process', $uuid, 'act']) !== null) ? 1 : 0,
                'act' => $sessionService->getSessionValue(['process', $uuid, 'act']) ?? 0,
                'max' => $sessionService->getSessionValue(['process', $uuid, 'max']) ?? 0,
            ]
        );
    }

}
