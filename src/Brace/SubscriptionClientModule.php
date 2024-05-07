<?php

namespace Lack\Subscription\Brace;

use Brace\Command\CliValueArgument;
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
        if (str_starts_with($endpoint, "/") || str_starts_with($endpoint, "file://")) {
            if (str_starts_with($endpoint, "file://"))
                $endpoint = substr($endpoint, 7);
            $this->manager = new FileSubscriptionManager($endpoint, $clientId);
            return;
        }
        $this->manager = new RemoteSubscriptionManager($endpoint, $clientId, $clientSecret);
    }

    public function register(BraceApp $app)
    {
        $app->define("subscriptionManager", new DiService(function() {
            return $this->manager;
        }));
        if ($app->has("command")) {
            $app->command->addGlobalArgument(new CliValueArgument("--subscription_id", "Subscription id"), function (string $value, BraceApp $app) {
                $app->define("subscription", new DiService(function() use ($value, $app) {
                    $manager = $app->get("subscriptionManager", SubscriptionManagerInterface::class);
                    return $manager->getSubscriptionById($value, true);
                }));
            });
        }
    }
}
