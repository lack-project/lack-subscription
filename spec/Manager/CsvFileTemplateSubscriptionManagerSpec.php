<?php

namespace spec\Lack\Subscription\Manager;

use PhpSpec\ObjectBehavior;

class CsvFileTemplateSubscriptionManagerSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith(__DIR__ . "/../../cfg/csv/subscriptions.csv");
    }

    function it_loads_a_subscription_from_a_template()
    {
        $subscription = $this->getSubscriptionById("sub123");

        $subscription->subscription_id->shouldBe("sub123");
        $subscription->clients["client1"]->active->shouldBe(true);
        $subscription->clients->shouldNotHaveKey("client2");
    }

    function it_lists_subscriptions_for_a_client()
    {
        $this->getSubscriptionsByClientId("client1")->shouldBe(["sub123"]);
    }
}
