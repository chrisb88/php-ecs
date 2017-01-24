<?php

namespace ecs\events;

use ecs\events\messages\Message;
use ecs\systems\System;

class EventManager
{
    /**
     * @var int
     */
    private static $receiverCount = 0;

    /**
     * @var Receiver[]
     */
    private $receivers;

    /**
     * Holds receiver IDs grouped by message class IDs
     * @var string[]
     */
    private $messageListeners;

    /**
     * @var System[]
     */
    private $mailboxes;

    public function __construct() {
        $this->receivers = [];
        $this->messageListeners = [];
        $this->mailboxes = [];
    }

    /**
     * @param Receiver $receiver
     * @param string $messageClass
     * @return $this
     */
    public function subscribe(Receiver $receiver, $messageClass) {
        $id = $this->fetchNewId();
        $this->receivers[$id] = $receiver;

        if (!isset($this->messageListeners[$messageClass])) {
            $this->messageListeners[$messageClass] = [];
        }

        $this->messageListeners[$messageClass][] = $id;

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
        if (!isset($this->mailboxes[$className])) {
            $this->mailboxes[$className] = [];
        }

        $this->mailboxes[$className][] = $system;
    }

    /**
     * @param Message $message
     * @throws \Exception
     */
    public function emit(Message $message) {
        $className = get_class($message);
        $listener = (isset($this->messageListeners[$className])) ? $this->messageListeners[$className] : [];
        foreach ($listener as $receiverId) {
            $this->getReceiver($receiverId)->receive($message);
        }

        /* @var System[] $systems */
        $systems = (isset($this->mailboxes[$className])) ? $this->mailboxes[$className] : [];
        foreach ($systems as $system) {
            $system->receiveMboxMessage($message);
        }
    }

    /**
     * @param $id
     * @return Receiver
     * @throws \Exception
     */
    private function getReceiver($id) {
        if (isset($this->receivers[$id])) {
            return $this->receivers[$id];
        }

        throw new \Exception(sprintf('Receiver with ID "%s" not found.', $id));
    }

    private function fetchNewId() {
        return ++static::$receiverCount;
    }
}
