<?php

namespace spec\Lack\Subscription\Manager;

use PhpSpec\ObjectBehavior;

class FileSubscriptionManagerSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith(__DIR__ . "/../../cfg/file");
    }

    function it_loads_an_existing_subscription()
    {
        $this->getSubscriptionById("sub123")->subscription_id->shouldBe("sub123");
    }

    function it_excludes_private_data_by_default()
    {
        $subscription = $this->getSubscriptionById("sub123");

        $subscription->shouldNotHavePrivateData();
        $subscription->clients["client1"]->public->shouldBeArray();
    }

    public function getMatchers(): array
    {
        return [
            "havePrivateData" => function ($subscription) {
                return isset($subscription->private) || isset($subscription->clients["client1"]->private);
            }
        ];
    }
}
