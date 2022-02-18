<?php

namespace Lack\Subscription\Type;

use Lack\Subscription\SubscriptionInterface;

class T_Subscription implements SubscriptionInterface
{

    public function __construct(
        /**
         * @bool
         */
        public bool $active,

        /**
         * @var int
         */
        public int $version,

        /**
         * @var string|null
         */
        public string|null $subscription_id = null,

        /**
         * @var string
         */
        public string $desc,

        /**
         * The main origin
         *
         * @var string
         */
        public string $origin,

        /**
         * @var string[]
         */
        public array $allow_origins,

        /**
         * @var T_ClientConfig[]|null
         */
        public array|null $clients = null,

        /**
         * @var array
         */
        public array $public = [],

        /**
         * @var array|null
         */
        public array|null $private = null
    ){}

    public function isAllowedOrigin(string $origin): bool
    {
        return false;
    }
}