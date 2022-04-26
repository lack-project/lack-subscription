<?php

namespace Lack\Subscription\Brace;

use Brace\Core\Base\BraceAbstractMiddleware;
use Lack\Subscription\SubscriptionManagerInterface;
use Phore\Di\Container\Producer\DiService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class SubscriptionMiddleware extends BraceAbstractMiddleware
{

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $subscriptionId = $request->getQueryParams()["subscription_id"] ?? null;
        $this->app->define("subscription", new DiService(function() {
            $manager = $this->app->get("subscriptionManager", SubscriptionManagerInterface::class);
            $manager
        }));
    }
}
