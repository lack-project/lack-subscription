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


    private $clientDataReader;

    public function __construct(
        string $rootDir,
        public string|null $clientId=null
    )
    {
        $this->clientDataReader = fn($input) => $input;
        $this->rootDir = phore_dir($rootDir)->assertDirectory();
    }


    protected function setClientDataReader(callable $reader) {
        $this->clientDataReader = $reader;
    }


    // Default: Validate the subscription id does not start with "tpl-" or "." and is at least 3 chars long
    protected $saveMode = true;

    public function getSubscriptionById(string $subscriptionId, bool $includePrivateData = false): T_Subscription
    {
        if ($this->saveMode && (startsWith($subscriptionId, "tpl-") || startsWith($subscriptionId, ".") || strlen(trim($subscriptionId)) < 3))
            throw new SubscriptionIdInvalidException("Invalid subscription id: '$subscriptionId' (Invalid value)");

        $subscriptionDir = $this->rootDir->withSubPath($subscriptionId);

        if ( ! $subscriptionDir->isDirectory())
            throw new SubscriptionIdInvalidException("subscription_id: '$subscriptionId' not found");

        $subscriptionDir = $subscriptionDir->assertDirectory();

        $mainFile = $subscriptionDir->withSubPath("_main.yml")->assertFile();

        $subscriptionData = $mainFile->get_yaml();
        $subscriptionData = ($this->clientDataReader)($subscriptionData);
        $subscriptionData = phore_hydrate($subscriptionData, T_Subscription::class);

        assert($subscriptionData instanceof T_Subscription);

        if ($subscriptionData->active === false)
            throw new SubscriptionIdInvalidException("subscription_id: '$subscriptionId' is not active");

        $subscriptionData->__clientId = $this->clientId;
        $subscriptionData->subscription_id = $subscriptionDir->getFilename();
        $subscriptionData->clients = [];

        if ( ! $includePrivateData) {
            unset ($subscriptionData->private);
        }

        foreach ($subscriptionDir->genWalk("*.yml") as $file) {

            if ($file->getFilename() === "_main")
                continue;
            if ($file->getFilename() !== $this->clientId && $this->clientId !== null)
                continue;
            $file = $file->assertFile();
            $clientConfig = $file->get_yaml();
            $clientConfig = ($this->clientDataReader)($clientConfig);
            $clientConfig = phore_hydrate($clientConfig, T_ClientConfig::class);

            if ( ! $includePrivateData) {
                unset($clientConfig->private);
            }

            if ( ! $clientConfig->active)
                continue;

            $subscriptionData->clients[$file->getFilename()] = $clientConfig;
        }

        return $subscriptionData;
    }

    public function getSubscriptionsByClientId(string $clientId = null) : array
    {
        if ($clientId === null)
            $clientId = $this->clientId;

          if ($clientId === null)
            throw new \InvalidArgumentException("ClientId is not set");

        $subscriptions = [];
        foreach ($this->rootDir->getListSorted() as $uri) {
            if (startsWith("tpl-", $uri->getFilename()) || startsWith(".", $uri->getFilename()))
                continue;
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
