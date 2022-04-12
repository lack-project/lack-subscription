<?php

namespace Lack\Subscription\Manager;

use Lack\Subscription\SubscriptionInterface;
use Lack\Subscription\SubscriptionManagerInterface;
use Lack\Subscription\Type\T_Subscription;

class RemoteSubscriptionManager implements SubscriptionManagerInterface
{


    public function __construct(
        public string $baseUrl,
        public ?string $clientId = null,
        public ?string $clientSecret = null
    ){
        if ( ! endsWith($this->baseUrl, "/"))
            $this->baseUrl .= "/";
    }


    public function getSubscriptionById(string $subscriptionId, string $clientId = null): SubscriptionInterface
    {
        $url = $this->baseUrl . "sub/{subscriptionId}/client/";
        if ($clientId !== null)
            $url .= "{clientId}";
        $req = phore_http_request($url, ["subscriptionId" => $subscriptionId, "clientId" => $clientId]);
        if ($this->clientId !== null)
            $req = $req->withBasicAuth($this->clientId, $this->clientSecret);

        $data = $req->send()->getBodyJson(T_Subscription::class);
        return $data;

    }

    public function getSubscriptionsByClientId(string $clientId): array
    {
        $url = $this->baseUrl . "client/?";
        $req = phore_http_request($url, [$subscriptionId, $clientId]);
        if ($this->clientId !== null)
            $req = $req->withBasicAuth($this->clientId, $this->clientSecret);
        return $req->send()->getBodyJson();
    }
}
