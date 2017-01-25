<?php

namespace ecs\events;

use ecs\utils\ArrayMultimap;
use ecs\events\messages\Message;
use ecs\systems\System;

class EventManager
{
    /**
     * @var ArrayMultimap
     */
    private $receiverMap;

    /**
     * @var ArrayMultimap
     */
    private $mailboxMap;

    public function __construct() {
        $this->receiverMap = new ArrayMultimap();
        $this->mailboxMap = new ArrayMultimap();
    }

    /**
     * @param Receiver $receiver
     * @param string $messageClass
     * @return $this
     */
    public function subscribe(Receiver $receiver, $messageClass) {
        $this->receiverMap->put($messageClass, $receiver);

        return $this;
    }

    public function unsubscribe() {
        throw new \Exception("Not implemented.");
    }

    /**
     * Subscribes a system to a mailbox of a particular message event.
     * @param string $className
     * @param System $system
     */
    public function subscribeMbox($className, System $system) {
        $this->mailboxMap->put($className, $system);
    }

    /**
     * @param Message $message
     * @throws \Exception
     */
    public function emit(Message $message) {
        $className = get_class($message);

        /* @var Receiver[] $listener */
        $listener = $this->receiverMap->get($className);
        foreach ($listener as $receiver) {
            $receiver->receive($message);
        }

        /* @var System[] $systems */
        $systems = $this->mailboxMap->get($className);
        foreach ($systems as $system) {
            $system->receiveMboxMessage($message);
        }
    }
}
