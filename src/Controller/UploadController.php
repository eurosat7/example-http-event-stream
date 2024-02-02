<?php

declare(strict_types=1);

namespace Eurosat7\ExampleHttpEventStream\Controller;

use Eurosat7\ExampleHttpEventStream\Service\SessionService;
use InvalidArgumentException;
use LogicException;
use Random\RandomException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Uid\UuidV6;

/**
 * @phpstan-ignore-next-line
 */
final class UploadController extends AbstractController
{
    private const string PROCESS = 'UploadController-process';

    #[Route('/upload', name: 'upload-index')]
    public function indexAction(): Response
    {
        return $this->render(
            'upload/index.html.twig',
            [
                'title' => 'index - upload',
                'url' => [
                    'upload-process' => $this->generateUrl('upload-process'),
                    'upload' => $this->generateUrl('upload-index'),
                ],
            ]
        );
    }

    #[Route('/upload/process', name: 'upload-process', methods: 'POST')]
    public function processAction(Request $request): Response
    {
        /** @var ?UploadedFile $fileInfo */
        $fileInfo = $request->files->get('upload');

        if ($fileInfo === null) {
            try {
                return new Response('no file was uploaded, or file not found', 500);
            } catch (InvalidArgumentException) {
                return $this->json(['error' => true]);
            }
        }

        $uuid = new UuidV6();
        return $this->render(
            'upload/process.html.twig',
            [
                'title' => 'process - upload',
                'url' => [
                    'upload-process-start' => $this->generateUrl('upload-process-start', ['id' => $uuid]),
                    'upload-process-status' => $this->generateUrl('upload-process-status', ['id' => $uuid]),
                ],
            ]
        );
    }

    /**
     * @throws LogicException
     */
    #[Route('/upload/process/{uuid}/start', name: 'upload-process-start')]
    public function processStartAction(string $uuid, SessionService $sessionService): JsonResponse
    {
        $act = 0;
        $max = 100;
        $sessionService->setSessionValue([self::PROCESS, $uuid, 'max'], $max);
        while ($act < $max) {
            try {
                $act += random_int(1, 4);
            } catch (RandomException) {
                $act += 1;
            }
            $sessionService->setSessionValue([self::PROCESS, $uuid, 'act'], $act);
        }
        return $this->json(true);
    }

    /**
     * x-throws LogicException
     */
    #[Route('/upload/process/{uuid}/status', name: 'upload-process-status')]
    public function processStatusAction(string $uuid, SessionService $sessionService): JsonResponse
    {
        return $this->json(
            [
                'error' => ($sessionService->getSessionValue([self::PROCESS, $uuid, 'act']) !== null) ? 1 : 0,
                'act' => $sessionService->getSessionValue([self::PROCESS, $uuid, 'act']) ?? 0,
                'max' => $sessionService->getSessionValue([self::PROCESS, $uuid, 'max']) ?? 0,
            ]
        );
    }

}
