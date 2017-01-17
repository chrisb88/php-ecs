<?php

namespace ecs\components;

use ecs\Component;

class Name extends Component
{
    private $value;

    public function __construct($value) {
        $this->value = $value;
    }
}
