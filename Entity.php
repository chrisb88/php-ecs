<?php

namespace ecs;

use ecs\events\EventManager;
use ecs\events\messages\ComponentAddedMessage;
use ecs\events\messages\ComponentRemovedMessage;

class Entity
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var EventManager
     */
    private $eventManager;

    /**
     * @var Component[]
     */
    private $components;

    /**
     * @param EntityManager $entityManager
     * @param EventManager $eventManager
     * @param int $id
     */
    public function __construct(EntityManager $entityManager, EventManager $eventManager, $id) {
        $this->id = $id;
        $this->components = [];
        $this->entityManager = $entityManager;
        $this->eventManager = $eventManager;
    }

    /**
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @throws \Exception
     */
    public function destroy() {
        throw new \Exception("Not implemented.");
    }

    /**
     * @param Component $component
     * @return $this
     * @throws \Exception
     */
    public function addComponent(Component $component) {
        $id = get_class($component);
        if (isset($this->components[$id])) {
            throw new \Exception(sprintf('Component "%s" is already registered.', $id));
        }

        $this->components[$id] = $component;
        $this->eventManager->emit(new ComponentAddedMessage($component));

        return $this;
    }

    public function addComponentIfNotExist(Component $component)
    {
        $id = get_class($component);
        if (!isset($this->components[$id])) {
            $this->addComponent($component);
        }

        return $this;
    }

    /**
     * @param string $className
     * @return Component
     * @throws \Exception
     */
    public function getComponent($className) {
        if (isset($this->components[$className])) {
            return $this->components[$className];
        }

        throw new \Exception(sprintf('Component "%s" is not registered.', $className));
    }

    /**
     * @param string $className
     * @return $this
     * @throws \Exception
     */
    public function removeComponent($className)
    {
        if (isset($this->components[$className])) {
            unset($this->components[$className]);
            $this->eventManager->emit(new ComponentRemovedMessage($className, $this));

            return $this;
        }

        throw new \Exception(sprintf('Component "%s" is not registered.', $className));
    }

    /**
     * @param string $className
     * @return bool
     */
    public function hasComponent($className) {
        return isset($this->components[$className]);
    }

    /**
     * @param string[] $classNames
     * @return bool
     */
    public function hasAllComponents(array $classNames) {
        foreach ($classNames as $name) {
            if (!$this->hasComponent($name)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param string[] $classNames
     * @return bool
     */
    public function hasAnyComponent(array $classNames) {
        foreach ($classNames as $name) {
            if ($this->hasComponent($name)) {
                return true;
            }
        }

        return false;
    }
}
