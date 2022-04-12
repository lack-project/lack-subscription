<?php

namespace Lack\Subscription;

interface SubscriptionManagerInterface
{

    public function getSubscriptionById(string $subscriptionId, string $clientId=null) : SubscriptionInterface;

    /**
     * @param string $clientId
     * @return array
     */
    public function getSubscriptionsByClientId(string $clientId) : array;

}
