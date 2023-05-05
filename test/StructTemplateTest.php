<?php

namespace Lack\Test;

use Lack\Subscription\Helper\StructTemplate;
use PHPUnit\Framework\TestCase;

class StructTemplateTest extends TestCase
{

    public function testBasic() {

        $tpl = [
            "name" => "{{ name : string }}",
            "age" => "{{ age : int }}",
            "is_admin" => "{{ is_admin : bool = false }}"
        ];
        $data = [
            "name" => "John",
            "age" => "42",
            "is_admin" => "true"
        ];

        $struct = new StructTemplate($data);
        $parsed = $struct->parse($tpl);

        $this->assertEquals([
            "name" => "John",
            "age" => 42,
            "is_admin" => true
        ], $parsed);


    }

}