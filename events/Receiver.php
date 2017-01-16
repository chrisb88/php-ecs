<?php

namespace ecs\events;

use ecs\events\messages\Message;

class Receiver
{
    /**
     * @var callable
     */
    private $callback;

    /**
     * @param callable $onReceiveCallback
     */
    public function __construct(callable $onReceiveCallback) {
        $this->callback = $onReceiveCallback;
    }

    /**
     * @param Message $message
     */
    public function receive(Message $message) {
        call_user_func($this->callback, $message);
    }
}
