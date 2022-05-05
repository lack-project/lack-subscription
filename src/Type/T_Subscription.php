<?php

namespace Lack\Subscription\Type;

use Lack\Subscription\SubscriptionInterface;

class T_Subscription implements SubscriptionInterface
{

    public function __construct(

        /**
         * @var string|null
         */
        public string|null $__clientId,

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
         * @var array<string, T_ClientConfig>|null
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
    ){
      
    }

    public function isAllowedOrigin(string $origin): bool
    {
        
        return origin_match($origin, $this->allow_origins);
    }


    /**
     * @template T
     * @param string $clientId
     * @param class-string<T> $cast
     * @return array|T
     */
    public function getClientPrivateConfig(string $clientId = null, string $cast=null) : array|object
    {
        if ($clientId === null)
            $clientId = $this->__clientId;
        $data = $this->clients[$clientId]->private;
        if ($cast !== null) {
            return phore_hydrate($data, $cast);
        }
        return $data;
    }

    /**
     * @template T
     * @param string $clientId
     * @param class-string<T> $cast
     * @return array|T
     */
    public function getClientPublicConfig(string $clientId = null, string $cast=null) : array|object
    {
        if ($clientId === null)
            $clientId = $this->__clientId;
        $data = $this->clients[$clientId]->public;
        if ($cast !== null) {
            return phore_hydrate($data, $cast);
        }
        return $data;
    }
}
