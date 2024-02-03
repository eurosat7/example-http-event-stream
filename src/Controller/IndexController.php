<?php

declare(strict_types=1);

namespace Eurosat7\ExampleHttpEventStream\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * @phpstan-ignore-next-line
 */
final class IndexController extends AbstractController
{
    #[Route('/', name: 'index-index')]
    public function indexAction(): Response
    {
        return $this->redirectToRoute('upload-index');
    }
}
