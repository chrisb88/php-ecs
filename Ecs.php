<?php

namespace ecs;

use ecs\events\EventManager;
use ecs\events\Receiver;
use ecs\systems\SystemInterface;
use Psr\Log\LoggerInterface;

class Ecs
{
    /**
     * @var LoggerInterface
     */
    private $logger;

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
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger = null) {
        $this->logger = $logger;
        $this->eventManager = new EventManager();
        $this->entityManager = new EntityManager($this->eventManager);
        $this->systemManager = new SystemManager($this->eventManager, $this->entityManager, $this->logger);
    }

    /**
     * Updates all systems.
     */
    public function update() {
        $this->systemManager->update();
        $this->eventManager->deliverDeferredMessages();
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
     * @param int $priority
     * @return $this
     * @throws \Exception
     */
    public function addSystem(SystemInterface $system, $priority = 0) {
        $this->systemManager->addSystem($system, $priority);

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
