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
     * @param EventManager $eventManager
     * @param EntityManager $entityManager
     */
    public function __construct(EventManager $eventManager, EntityManager $entityManager) {
        $this->systems = [];
        $this->eventManager = $eventManager;
        $this->entityManager = $entityManager;
    }

    /**
     * Updates all systems.
     */
    public function update() {
        foreach ($this->systems as $system) {
            $system->update();
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
     * @return $this
     * @throws \Exception if the system was already added
     */
    public function addSystem(SystemInterface $system) {
        $id = get_class($system);
        if (isset($this->systems[$id])) {
            throw new \Exception(sprintf('System "%s" already registered.', $id));
        }

        $this->systems[$id] = $system;
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
