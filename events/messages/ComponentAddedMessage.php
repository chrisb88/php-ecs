<?php

namespace ecs\events\messages;

use ecs\Component;

class ComponentAddedMessage extends Message
{
    /**
     * @var Component
     */
    private $component;

    /**
     * @param Component $component
     */
    public function __construct(Component $component) {
        $this->component = $component;
    }

    public function getComponent() {
        return $this->component;
    }
}
