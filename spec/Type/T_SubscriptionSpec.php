<?php

namespace spec\Lack\Subscription\Type;

use Lack\Subscription\Type\T_ClientConfig;
use PhpSpec\ObjectBehavior;

class T_SubscriptionSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith(
            "client1",
            true,
            1,
            "sub123",
            "Test subscription",
            "https://example.com",
            [],
            [
                "client1" => new T_ClientConfig(true, ["active" => true, "public" => []], ["active" => false, "public" => []]),
                "client2" => new T_ClientConfig(true, ["active" => false, "public" => []], ["active" => true, "public" => []])
            ]
        );
    }

    function it_returns_private_config_for_an_explicit_client()
    {
        $this->getClientPrivateConfig("client2")->shouldBe(["active" => true, "public" => []]);
        $this->getClientPrivateConfig("client2", null)->shouldBe(["active" => true, "public" => []]);
    }

    function it_uses_the_default_client_for_private_config()
    {
        $this->getClientPrivateConfig()->shouldBe(["active" => false, "public" => []]);
        $this->getClientPrivateConfig(null, null)->shouldBe(["active" => false, "public" => []]);
    }

    function it_hydrates_private_config_when_a_cast_is_given()
    {
        $this->getClientPrivateConfig("client2", T_ClientConfig::class)->shouldHaveType(T_ClientConfig::class);
        $this->getClientPrivateConfig("client2", T_ClientConfig::class)->active->shouldBe(true);
        $this->getClientPrivateConfig(null, T_ClientConfig::class)->active->shouldBe(false);
    }

    function it_returns_public_config_for_an_explicit_client()
    {
        $this->getClientPublicConfig("client2")->shouldBe(["active" => false, "public" => []]);
        $this->getClientPublicConfig("client2", null)->shouldBe(["active" => false, "public" => []]);
    }

    function it_uses_the_default_client_for_public_config()
    {
        $this->getClientPublicConfig()->shouldBe(["active" => true, "public" => []]);
        $this->getClientPublicConfig(null, null)->shouldBe(["active" => true, "public" => []]);
    }

    function it_hydrates_public_config_when_a_cast_is_given()
    {
        $this->getClientPublicConfig("client2", T_ClientConfig::class)->shouldHaveType(T_ClientConfig::class);
        $this->getClientPublicConfig("client2", T_ClientConfig::class)->active->shouldBe(false);
        $this->getClientPublicConfig(null, T_ClientConfig::class)->active->shouldBe(true);
    }
}
