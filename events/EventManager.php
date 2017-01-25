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

    /**
     * @var array
     */
    private $deferredMessages;

    public function __construct() {
        $this->receiverMap = new ArrayMultimap();
        $this->mailboxMap = new ArrayMultimap();
        $this->deferredMessages = [];
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
     * Emits a message immediately to all subscribers.
     * @param Message $message
     */
    public function emit(Message $message) {
        $className = get_class($message);

        /* @var Receiver[] $listener */
        $listener = $this->receiverMap->get($className);
        foreach ($listener as $receiver) {
            $receiver->receive($message);
        }

        $this->deliverMailboxMessage($message);
    }

    /**
     * Stores a message in a queue to be emitted to subscribers at the end of the update cycle.
     * @param Message $message
     */
    public function emitDeferred(Message $message) {
        $this->deferredMessages[] = $message;
    }

    /**
     * Delivers queued messages to the subscribers mailboxes.
     * This should be called at the end of the update cycle.
     */
    public function deliverDeferredMessages() {
        while (count($this->deferredMessages) > 0) {
            /* @var Message $message */
            $message = array_shift($this->deferredMessages);
            $this->deliverMailboxMessage($message);
        }
    }

    /**
     * Delivers a message to their subscribers mailbox.
     * @param Message $message
     */
    private function deliverMailboxMessage(Message $message) {
        $className = get_class($message);

        /* @var System[] $systems */
        $systems = $this->mailboxMap->get($className);
        foreach ($systems as $system) {
            $system->receiveMboxMessage($message);
        }
    }
}
