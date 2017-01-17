<?php

namespace ecs\events\messages;

use ecs\systems\SystemInterface;

class SystemAddedMessage extends Message
{
    /**
     * @var System
     */
    private $system;

    /**
     * @param SystemInterface $system
     */
    public function __construct(SystemInterface $system) {
        $this->system = $system;
    }

    public function getSystem() {
        return $this->system;
    }
}
