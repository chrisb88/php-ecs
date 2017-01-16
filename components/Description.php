<?php

namespace ecs\components;

use ecs\Component;

require_once('Component.php');

class Description extends Component
{
    private $value;

    public function __construct($value) {
        $this->value = $value;
    }
}
