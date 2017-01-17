<?php

namespace ecs\events;

use ecs\events\messages\Message;

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

    public function __construct() {
        $this->receivers = [];
        $this->messageListeners = [];
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
     * @param Message $message
     * @throws \Exception
     */
    public function emit(Message $message) {
        $type = get_class($message);
        $listener = (isset($this->messageListeners[$type])) ? $this->messageListeners[$type] : [];
        foreach ($listener as $receiverId) {
            $this->getReceiver($receiverId)->receive($message);
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
