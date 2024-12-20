<?php

declare(strict_types=1);

namespace App\Forwarder\Newsletter\Identify;

use App\CDP\Analytics\Model\Subscription\Identify\IdentifyModel;
use App\CDP\Analytics\Model\Subscription\Identify\SubscriptionStartMapper;
use App\DTO\Newsletter\NewsletterWebhook;
use App\Forwarder\Newsletter\ForwarderInterface;

class SubscribtionStartForwarder implements ForwarderInterface
{
    private const string SUPPORTED_EVENT = 'newsletter_subscribed';
    public function supports(NewsletterWebhook $newsletterWebhook): bool
    {
        return $newsletterWebhook->getEvent() === self::SUPPORTED_EVENT;
    }

    public function forward(NewsletterWebhook $newsletterWebhook): void
    {
        // Instantiate a class which models Identify data
        $model = new IdentifyModel();

        // Map The NewsletterWebhook data to the model
        (new SubscriptionStartMapper())->map($newsletterWebhook, $model);
        //Validate the model
        dd($model);
        // Use the CDP client to POST the data to the CDP
    }
}
