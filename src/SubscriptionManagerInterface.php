<?php

namespace Lack\Subscription;

use Lack\Subscription\Type\T_Subscription;

interface SubscriptionManagerInterface
{
    
    
    public function getSubscriptionById(string $subscriptionId, bool $includePrivateData = false) : T_Subscription;

    /**
     * @param string $clientId
     * @return string[]     All active SubscriptionIds
     */
    public function getSubscriptionsByClientId(string $clientId = null) : array;

}
