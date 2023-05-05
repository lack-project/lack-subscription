<?php

namespace Lack\Subscription;

use Lack\Subscription\Ex\SubscriptionIdInvalidException;
use Lack\Subscription\Type\T_Subscription;

interface SubscriptionManagerInterface
{

    public function setClientId(string $clientId = null) : void;

    /**
     *
     * @throws SubscriptionIdInvalidException
     * @param string $subscriptionId
     * @param bool $includePrivateData
     * @return T_Subscription
     */
    public function getSubscriptionById(string $subscriptionId, bool $includePrivateData = false) : T_Subscription;

    /**
     * @param string $clientId
     * @return string[]     All active SubscriptionIds
     */
    public function getSubscriptionsByClientId(string $clientId = null) : array;

}
