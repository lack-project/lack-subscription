<?php

namespace Lack\Subscription;

interface SubscriptionManagerInterface
{

    public function getSubscriptionById(string $subscriptionId, string $clientId=null) : SubscriptionInterface;

}