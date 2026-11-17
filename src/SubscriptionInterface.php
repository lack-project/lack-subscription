<?php

namespace Lack\Subscription;

interface SubscriptionInterface
{

    public function isAllowedOrigin(string $origin) : bool;

    /**
     * @template T
     * @param string|null $clientId
     * @param class-string<T>|null $cast
     * @return T
     */
    public function getClientPrivateConfig(?string $clientId=null, ?string $cast=null);
    
     /**
     * @template T
     * @param string|null $clientId
     * @param class-string<T>|null $cast
     * @return array|T
     */
    public function getClientPublicConfig(?string $clientId=null, ?string $cast=null) : array|object;
    
}