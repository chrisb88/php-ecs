<?php

namespace ecs;

use ecs\events\EventManager;
use ecs\events\messages\SystemAddedMessage;
use ecs\systems\SystemInterface;

class SystemManager
{
    /**
     * @var EventManager
     */
    private $eventManager;

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var SystemInterface[]
     */
    private $systems;

    /**
     * @var int[]
     */
    private $priorities;

    /**
     * @param EventManager $eventManager
     * @param EntityManager $entityManager
     */
    public function __construct(EventManager $eventManager, EntityManager $entityManager) {
        $this->systems = [];
        $this->priorities = [];
        $this->eventManager = $eventManager;
        $this->entityManager = $entityManager;
    }

    /**
     * Updates all systems.
     */
    public function update() {
        var_dump($this->priorities);
        foreach ($this->priorities as $className => $priority) {
            $this->getSystem($className)->update();
        }
    }

    /**
     * @param string $className
     * @return SystemInterface
     */
    public function createSystem($className) {
        return new $className($this->eventManager, $this->entityManager);
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
     * @return SystemInterface
     * @throws \Exception
     */
    public function getSystem($className) {
        if (isset($this->systems[$className])) {
            return $this->systems[$className];
        }

        throw new \Exception(sprintf('System "%s" not found.', $className));
    }
}
