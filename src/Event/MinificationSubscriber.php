<?php

declare(strict_types=1);

namespace App\Event;

use Override;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelEvents;

class MinificationSubscriber implements EventSubscriberInterface
{
    // phpdoc can't understand constant type declaration yet: private const array
    private const REPLACEMENT_MAP
        = [
            '/<\/script>/' => "</script>\n",
            "/[\r\n]+/" => "\n",
            "/\t/" => ' ',
            '/[ ]+/' => ' ',
            '/ </' => '<',
            '/> /' => '>',
        ];

    #[Override]
    /**
     * @return array<string,array{string,int}>
     */
    public static function getSubscribedEvents(): array
    {
        {
            return [
                KernelEvents::RESPONSE => ['onKernelResponse', -256],
            ];
        }
    }

    public function onKernelResponse(ResponseEvent $event): void
    {
        if (
            $event->getRequestType() !== HttpKernelInterface::MAIN_REQUEST
        ) {
            return;
        }

        /* this is an internal method - we should search explicitly at the right place
         * this is a perfect example of copying bad code into your code base. :(
         *
         * if (
         *   $event->getRequest()->get('_route') === 'admin' // don't apply on admin pages
         * ) {
         *   return;
         * }
         */

        $response = $event->getResponse();

        $content = $response->getContent();
        if ($content === false) {
            $content = '';
        }
        $response->setContent(
            preg_replace(
                array_keys(self::REPLACEMENT_MAP),
                array_values(self::REPLACEMENT_MAP),
                $content
            )
        );
    }
}
