<?php

namespace Lack\Subscription\Brace;

use Brace\Core\Base\CallbackMiddleware;
use Brace\Core\BraceApp;
use Brace\Core\BraceModule;
use Lack\Subscription\Manager\FileSubscriptionManager;
use Lack\Subscription\Manager\RemoteSubscriptionManager;
use Lack\Subscription\SubscriptionManagerInterface;
use Phore\Di\Container\Producer\DiService;

class SubscriptionClientModule implements BraceModule
{

    private SubscriptionManagerInterface $manager;

    public function __construct($endpoint, $clientId=null, $clientSecret=null)
    {
        if (str_starts_with($endpoint, "/")) {
            $this->manager = new FileSubscriptionManager($endpoint);
            return;
        }
        $this->manager = new RemoteSubscriptionManager($endpoint, $clientId, $clientSecret);
    }

    public function register(BraceApp $app)
    {
        $app->define("subscriptionManager", new DiService(function() {
            return $this->manager;
        }));
    }
}
