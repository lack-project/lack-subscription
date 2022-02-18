<?php

namespace Lack\Subscription\Type;

class T_ClientConfig
{
    public function __construct(

        /**
         * @bool
         */
        public bool $active,

        /**
         * @var array
         */
        public array $public = [],

        /**
         * @var array|null
         */
        public array|null $private = null

    ){}
}