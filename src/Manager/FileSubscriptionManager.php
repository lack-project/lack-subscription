<?php

namespace Lack\Subscription\Manager;

use Lack\Subscription\Ex\SubscriptionIdInvalidException;
use Lack\Subscription\SubscriptionInterface;
use Lack\Subscription\SubscriptionManagerInterface;
use Lack\Subscription\Type\T_ClientConfig;
use Lack\Subscription\Type\T_Subscription;

class FileSubscriptionManager implements SubscriptionManagerInterface
{

    /**
     * @var \Phore\FileSystem\PhoreDirectory
     */
    private $rootDir;



    public function __construct(
        string $rootDir,
        public string|null $clientId=null
    )
    {
        $this->rootDir = phore_dir($rootDir)->assertDirectory();
    }


    public function getSubscriptionById(string $subscriptionId, bool $includePrivateData = false): SubscriptionInterface|T_Subscription
    {
        $subscriptionDir = $this->rootDir->withSubPath($subscriptionId);
        if ( ! $subscriptionDir->isDirectory())
            throw new SubscriptionIdInvalidException("subscription_id: '$subscriptionId' not found");

        $subscriptionDir = $subscriptionDir->assertDirectory();

        $mainFile = $subscriptionDir->withSubPath("_main.yml")->assertFile();
        $subscriptionData = $mainFile->get_yaml(cast: T_Subscription::class);

        assert($subscriptionData instanceof T_Subscription);

        $subscriptionData->__clientId = $this->clientId;
        $subscriptionData->subscription_id = $subscriptionDir->getFilename();
        $subscriptionData->clients = [];

        if ( ! $includePrivateData) {
            unset ($subscriptionData->private);
        }

        foreach ($subscriptionDir->genWalk("*.yml") as $file) {
            if ($file->getFilename() !== $this->clientId)
                continue;
            $file = $file->assertFile();
            $clientConfig = $file->get_yaml(T_ClientConfig::class);
            if ( ! $includePrivateData) {
                unset($clientConfig->private);
            }

            if ( ! $clientConfig->active)
                continue;

            $subscriptionData->clients[$file->getFilename()] = $clientConfig;
        }

        return $subscriptionData;
    }

    public function getSubscriptionsByClientId(string $clientId) : array
    {
        $subscriptions = [];
        foreach ($this->rootDir->getListSorted() as $uri) {

            $main = $uri->withSubPath("_main.yml");
            if ( ! $main->exists())
                continue;
            $config = $uri->withSubPath($clientId . ".yml");
            if ( ! $config->exists())
                continue;
            $subscriptions[] = $uri->getRelPath();
        }
        return $subscriptions;
    }

}
