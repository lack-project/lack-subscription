<?php

namespace spec\Lack\Subscription\Helper;

use PhpSpec\ObjectBehavior;

class StructTemplateSpec extends ObjectBehavior
{
    function it_parses_typed_template_values()
    {
        $this->beConstructedWith([
            "name" => "John",
            "age" => "42",
            "is_admin" => "true"
        ]);

        $this->parse([
            "name" => "{{ name : string }}",
            "age" => "{{ age : int }}",
            "is_admin" => "{{ is_admin : bool = false }}"
        ])->shouldBe([
            "name" => "John",
            "age" => 42,
            "is_admin" => true
        ]);
    }
}
