<?php

namespace Lack\Subscription\Brace;

use Brace\Auth\Basic\AuthBasicMiddleware;
use Brace\Auth\Basic\Validator\SubscriptionAuthValidator;

class SubscriptionBasicAuthValidationMiddleware extends AuthBasicMiddleware
{

    public function __construct()
    {
        parent::__construct(new SubscriptionAuthValidator(), true);
    }
}
