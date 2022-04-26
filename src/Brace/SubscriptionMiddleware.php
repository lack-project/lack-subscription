<?php

namespace Lack\Subscription\Brace;

use Brace\Core\Base\BraceAbstractMiddleware;
use Brace\Router\Type\RouteParams;
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
        if ($this->app->has("routeParams")) {
            $rp = $this->app->get("routeParams", RouteParams::class);
            if ($rp->has("subscription_id"))
                $subscriptionId = $rp->get("subscription_id");
        }
        $this->app->define("subscription", new DiService(function() use ($subscriptionId) {
            if ($subscriptionId === null)
                throw new \InvalidArgumentException("Parameter subscription_id is missing");
            $manager = $this->app->get("subscriptionManager", SubscriptionManagerInterface::class);
            return $manager->getSubscriptionById($subscriptionId, true);
        }));
        return $handler->handle($request);
    }
}
