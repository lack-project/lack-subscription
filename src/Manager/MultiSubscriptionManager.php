<?php

namespace Lack\Subscription\Manager;

use Lack\Subscription\Ex\SubscriptionIdInvalidException;
use Lack\Subscription\SubscriptionManagerInterface;
use Lack\Subscription\Type\T_Subscription;

class MultiSubscriptionManager implements SubscriptionManagerInterface
{

    /**
     * @param SubscriptionManagerInterface[] $managers
     */
    public function __construct(
        private array $managers = []
    ) {

    }

    public function addSubscriptionManager(SubscriptionManagerInterface $manager) {
        $this->managers[] = $manager;
    }

    public function getSubscriptionById(string $subscriptionId, bool $includePrivateData = false): T_Subscription
    {
        foreach ($this->managers as $curManager) {
            try {
                $subscription = $curManager->getSubscriptionById($subscriptionId, $includePrivateData);
                return $subscription;
            } catch (SubscriptionIdInvalidException $e) {
            }
        }
        throw new SubscriptionIdInvalidException("subscription_id: '$subscriptionId' not found");
    }

    public function getSubscriptionsByClientId(string $clientId = null): array
    {
        $subscriptions = [];
        foreach ($this->managers as $curManager) {
            $curSubscriptions = $curManager->getSubscriptionsByClientId($clientId);
            $subscriptions = array_merge($subscriptions, $curSubscriptions);
        }
        return $subscriptions;
    }
}