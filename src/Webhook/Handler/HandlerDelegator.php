<?php

declare(strict_types=1);

namespace App\Webhook\Handler;

use App\DTO\Webhook;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;

class HandlerDelegator
{
    /**
     * @param iterable<WebhookHandlerInterface> $handlers
     */
    public function __construct(
        #[AutowireIterator('webhook.handler')] private readonly iterable $handlers
    ) {
    }
    public function delegate(Webhook $webhook): void
    {
        //Loop over the handlers
        foreach ($this->handlers as $handler) {
            //Ask if supported
            if ($handler->supports($webhook)) {
                // If supported, call handle
                $handler->handle($webhook);
            }
        }
    }
}
