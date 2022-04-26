<?php

namespace Lack\Subscription;

interface SubscriptionInterface
{

    public function isAllowedOrigin(string $origin) : bool;

    /**
     * @template T
     * @param string $clientId
     * @param class-string<T> $cast
     * @return array|T
     */
    public function getClientPrivateConfig(string $clientId=null, string $cast=null) : array|object;
    
     /**
     * @template T
     * @param string $clientId
     * @param class-string<T> $cast
     * @return array|T
     */
    public function getClientPublicConfig(string $clientId=null, string $cast=null) : array|object;
    
}