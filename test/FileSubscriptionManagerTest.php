<?php

namespace Lack\Test;

use Lack\Subscription\Manager\FileSubscriptionManager;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
class FileSubscriptionManagerTest extends TestCase
{


    public function testLoadExistingSubId()
    {
        $m = new FileSubscriptionManager("/opt/cfg");
        $sub = $m->getSubscriptionById("sub123");
        $this->assertEquals("sub123", $sub->subscription_id);
    }

    public function testPrivateSectionWasUnsetByDefault()
    {
        $m = new FileSubscriptionManager("/opt/cfg");
        $sub = $m->getSubscriptionById("sub123", "client1");

        $this->assertEquals(false, isset($sub->private));
        $this->assertEquals(false, isset($sub->clients["client1"]->private));
        $this->assertEquals(true, isset($sub->clients["client1"]->public));

    }

}