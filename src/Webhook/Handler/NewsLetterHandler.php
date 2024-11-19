<?php

namespace App\Webhook\Handler;

use App\DTO\Newsletter\Factory\NewsletterWebhookFactory;
use App\DTO\Webhook;
use App\Forwarder\Newsletter\ForwarderInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;

class NewsLetterHandler implements WebhookHandlerInterface
{
    private const array SUPPORTED_TYPES = [
        'newsletter_opened',
        'newsletter_subscribed',
        'newsletter_unsubscribed',
    ];

    /**
     * @param iterable<ForwarderInterface> $forwarder
     */
    public function __construct(
        private readonly NewsletterWebhookFactory $newsletterWebhookFactory,
        #[AutowireIterator('forwarder.newsletter')] private iterable $forwarder,
    ) {
    }

    public function supports(Webhook $webhook): bool
    {
        return in_array($webhook->getEvent(), self::SUPPORTED_TYPES);
    }

    public function handle(Webhook $webhook): void
    {
        $newsletterWebhook = $this->newsletterWebhookFactory->create($webhook);

        //LOop over tht forwarders
        foreach ($this->forwarder as $forwarder) {
            // If supported
            if ($forwarder->supports($newsletterWebhook)) {
                // Forward the data
                $forwarder->forward($newsletterWebhook);
            }
        }
    }
}
