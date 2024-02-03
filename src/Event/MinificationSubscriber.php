<?php
declare(strict_types=1);

namespace Eurosat7\ExampleHttpEventStream\Event;

use Override;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelEvents;

class MinificationSubscriber implements EventSubscriberInterface
{
    #[Override]
    public static function getSubscribedEvents(): array
    {
        {
            return [
                KernelEvents::RESPONSE => ['onKernelResponse', -256]
            ];
        }
    }

    public function onKernelResponse(ResponseEvent $event): void
    {
        if (
            $event->getRequestType() != HttpKernelInterface::MAIN_REQUEST
            || $event->getRequest()->get('_route') === 'admin' // don't apply on admin pages
        ) {
            return;
        }

        $response = $event->getResponse();

        $replace = [
            '/<\/script>/' => "</script>\n",
            "/[\r\n]+/" => "\n",
            "/\t/" => ' ',
            '/[ ]+/' => ' ',
            '/ </' => '<',
            '/> /' => '>',
        ];

        $response->setContent(preg_replace(
            array_keys($replace),
            array_values($replace),
            $response->getContent()
        ));
    }
}
