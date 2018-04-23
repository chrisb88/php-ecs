<?php

namespace ecs;

use ecs\events\EventManager;
use ecs\events\messages\SystemAddedMessage;
use ecs\systems\System;
use ecs\systems\SystemInterface;
use Psr\Log\LoggerInterface;

class SystemManager
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var EventManager
     */
    private $eventManager;

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var System[]
     */
    private $systems;

    /**
     * @var int[]
     * @todo maybe better use @see http://php.net/manual/de/class.splheap.php for priorities?
     */
    private $priorities;

    /**
     * @param EventManager $eventManager
     * @param EntityManager $entityManager
     * @param LoggerInterface $logger
     */
    public function __construct(EventManager $eventManager, EntityManager $entityManager, LoggerInterface $logger = null)
    {
        $this->systems = [];
        $this->priorities = [];
        $this->logger = $logger;
        $this->eventManager = $eventManager;
        $this->entityManager = $entityManager;
    }

    /**
     * Updates all systems.
     */
    public function update() {
        foreach ($this->priorities as $className => $priority) {
            $this->getSystem($className)->update();
        }
    }

    /**
     * @param string $className
     * @return System
     */
    public function createSystem($className) {
        return new $className($this->eventManager, $this->entityManager, $this->logger);
    }

    /**
     * @param SystemInterface $system
     * @param int $priority
     * @return $this
     * @throws \Exception if the system was already added
     */
    public function addSystem(SystemInterface $system, $priority = 0) {
        $id = get_class($system);
        if (isset($this->systems[$id])) {
            throw new \Exception(sprintf('System "%s" is already registered.', $id));
        }

        $this->systems[$id] = $system;
        $this->priorities[$id] = (int) $priority;
        asort($this->priorities);

        $this->eventManager->emit(new SystemAddedMessage($system));

        return $this;
    }

    /**
     * @param string $className
     * @return System
     * @throws \Exception
     */
    public function getSystem($className) {
        if (isset($this->systems[$className])) {
            return $this->systems[$className];
        }

        throw new \Exception(sprintf('System "%s" not found.', $className));
    }
}
