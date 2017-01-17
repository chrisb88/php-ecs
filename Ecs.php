<?php

namespace ecs;

use ecs\events\EventManager;
use ecs\events\Receiver;
use ecs\systems\SystemInterface;

class Ecs
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var EventManager
     */
    private $eventManager;

    /**
     * @var SystemManager
     */
    private $systemManager;

    /**
     * Constructor
     */
    public function __construct() {
        $this->eventManager = new EventManager();
        $this->entityManager = new EntityManager($this->eventManager);
        $this->systemManager = new SystemManager($this->eventManager, $this->entityManager);
    }

    /**
     * Updates all systems.
     */
    public function update() {
        $this->systemManager->update();
    }

    /**
     * @return Entity
     */
    public function createEntity() {
        return $this->entityManager->createEntity();
    }

    /**
     * @param int $id Entity ID
     * @return Entity
     * @throws \Exception
     */
    public function getEntity($id) {
        return $this->entityManager->getEntity($id);
    }

    /**
     * @param int $id Entity ID
     */
    public function destroyEntity($id) {
        $this->entityManager->destroyEntity($id);
    }

    /**
     * @param Receiver $receiver
     * @param string $messageClass
     * @return $this
     */
    public function subscribe(Receiver $receiver, $messageClass) {
        $this->eventManager->subscribe($receiver, $messageClass);

        return $this;
    }

    /**
     * @param int $entityId Entity ID
     * @param Component $component
     * @return $this
     */
    public function addComponent($entityId, Component $component) {
        $this->getEntity($entityId)->addComponent($component);

        return $this;
    }

    /**
     * @param string $className
     * @return SystemInterface
     */
    public function createSystem($className) {
        return $this->systemManager->createSystem($className);
    }

    /**
     * @param SystemInterface $system
     * @return $this
     */
    public function addSystem(SystemInterface $system) {
        $this->systemManager->addSystem($system);

        return $this;
    }

    /**
     * @param string $className
     * @return SystemInterface
     * @throws \Exception
     */
    public function getSystem($className) {
        return $this->systemManager->getSystem($className);
    }
}
