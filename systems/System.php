<?php

namespace ecs\systems;

use ecs\EntityManager;
use ecs\events\EventManager;
use ecs\events\messages\Message;
use SplQueue;

abstract class System implements SystemInterface
{
    /**
     * @var EventManager
     */
    protected $eventManager;

    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var SplQueue[]
     */
    protected $mailboxes;

    /**
     * @param EventManager $eventManager
     * @param EntityManager $entityManager
     */
    public function __construct(EventManager $eventManager, EntityManager $entityManager) {
        $this->eventManager = $eventManager;
        $this->entityManager = $entityManager;
        $this->mailboxes = [];
        $this->init();
    }

    public function init() {}

    /**
     * Subscribes this system to message events.
     * @param string $className Message class to subscribe for message events
     */
    public function subscribeMbox($className) {
        if (!isset($this->mailboxes[$className])) {
            $this->mailboxes[$className] = new SplQueue();
        }

        $this->eventManager->subscribeMbox($className, $this);
    }

    /**
     * @param string $className
     * @return SplQueue
     * @throws \Exception
     */
    public function getMailbox($className) {
        if (isset($this->mailboxes[$className])) {
            return $this->mailboxes[$className];
        }

        throw new \Exception(sprintf('Mailbox "%s" not subscribed.', $className));
    }

    /**
     * @param Message $message
     */
    public function receiveMboxMessage(Message $message) {
        $className = get_class($message);
        $this->mailboxes[$className]->enqueue($message);
    }

    public function eachMbox($className, callable $func) {
        $mb = $this->getMailbox($className);
        while (!$mb->isEmpty()) {
            /* @var Message $msg */
            $msg = $mb->dequeue();
            call_user_func($func, $msg);
        }
    }
}
