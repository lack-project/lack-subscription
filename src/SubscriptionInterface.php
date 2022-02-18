<?php

namespace Lack\Subscription;

interface SubscriptionInterface
{

    public function isAllowedOrigin(string $origin) : bool;

}