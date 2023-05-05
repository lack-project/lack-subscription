<?php

namespace Lack\Test;

use Lack\Subscription\Manager\CsvFileTemplateSubscriptionManager;
use PHPUnit\Framework\TestCase;

class CsvFileSubscriptionManagerTest extends TestCase
{

    public function testGetSubscription() {
        $m = new CsvFileTemplateSubscriptionManager(__DIR__ . "/../cfg/csv/subscriptions.csv");
        $sub = $m->getSubscriptionById("sub123");

        $this->assertEquals("sub123", $sub->subscription_id);
        $this->assertEquals(true, $sub->clients["client1"]->active);
        $this->assertEquals(false, isset($sub->clients["client2"]));
    }


    public function testGetAllSubscriptions() {
        $m = new CsvFileTemplateSubscriptionManager(__DIR__ . "/../cfg/csv/subscriptions.csv");
        $subs = $m->getSubscriptionsByClientId("client1");

        $this->assertEquals(["sub123"], $subs);
    }
}