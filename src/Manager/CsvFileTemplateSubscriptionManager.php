<?php

namespace Lack\Subscription\Manager;

use Lack\Subscription\Ex\SubscriptionIdInvalidException;
use Lack\Subscription\Helper\StructTemplate;
use Lack\Subscription\SubscriptionManagerInterface;

class CsvFileTemplateSubscriptionManager extends FileSubscriptionManager
{

    public function __construct(
        private string $csvFile,
        string $rootDir=null,
        private string $subscriptionFieldName = "subscription_id",
        private string $templateFieldName="template",
        private $csvOptions = ["delimiter" => ",", "header" => true, "skip_invalid"=>true]
    )
    {
        if ($rootDir === null)
            $rootDir = dirname($csvFile);
        parent::__construct($rootDir);
        $this->saveMode = false; // Do not validate subscription id (to load templates)
    }



    public function getSubscriptionById(string $subscriptionId, bool $includePrivateData = false): \Lack\Subscription\Type\T_Subscription
    {

        $csv = phore_file($this->csvFile)->parseCsv($this->csvOptions);
        foreach ($csv as $row) {
            if ( ! isset ($row[$this->subscriptionFieldName]))
                throw new \InvalidArgumentException("Field '$this->subscriptionFieldName' not found in csv file '$this->csvFile'");
            if ( ! isset ($row[$this->templateFieldName]))
                throw new \InvalidArgumentException("Field '$this->templateFieldName' not found in csv file '$this->csvFile'");

            $curSubscriptionId = $row[$this->subscriptionFieldName];
            if ($curSubscriptionId !== $subscriptionId) {
                continue;
            }
            $template = $row[$this->templateFieldName];
            if ( ! startsWith($template, "tpl-"))
                throw new SubscriptionIdInvalidException("Invalid template id: '$template' (Must start with 'tpl-') for subscription_id: '$subscriptionId'");

            $this->setClientDataReader(function($input) use ($row) {
                $parser = new StructTemplate($row);
                return $parser->parse($input); // Parse the input with the row data
            });
            $curSub = parent::getSubscriptionById($template, $includePrivateData);
            $curSub->subscription_id = $curSubscriptionId; // Modify the Subscriptin Id
            return $curSub;
        }
        throw new SubscriptionIdInvalidException("subscription_id: '$subscriptionId' not found");
    }

    public function getSubscriptionsByClientId(string $clientId = null): array
    {
        $csv = phore_file($this->csvFile)->parseCsv($this->csvOptions);
        $subscriptions = [];
        foreach ($csv as $row) {
            if ( ! isset ($row[$this->subscriptionFieldName]))
                throw new \InvalidArgumentException("Field '$this->subscriptionFieldName' not found in csv file '$this->csvFile'");
            $curSubId = $row[$this->subscriptionFieldName];
            $subscriptionData = $this->getSubscriptionById($curSubId);
            if (isset ($subscriptionData->clients[$clientId])) {
                $subscriptions[] = $curSubId;
            }
        }
        return $subscriptions;
    }


    public function setClientId(string $clientId = null): void
    {
        $this->clientId = $clientId;
    }
}