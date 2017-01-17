<?php

namespace ecs;

use ecs\events\EventManager;
use ecs\events\messages\EntityCreatedMessage;
use ecs\events\messages\EntityDestroyedMessage;

class EntityManager
{
    /**
     * @var int
     */
    private static $entityCount = 0;

    /**
     * @var EventManager
     */
    private $eventManager;

    /**
     * @var Entity[]
     */
    private $entities;

    /**
     * @var int[]
     */
    private $entityFreeIds;

    /**
     * @param EventManager $eventManager
     */
    public function __construct(EventManager $eventManager) {
        $this->entities = [];
        $this->entityFreeIds = [];
        $this->eventManager = $eventManager;
    }

    /**
     * @return Entity
     */
    public function createEntity() {
        $id = $this->fetchNewId();
        $entity = new Entity($this, $id);
        $this->entities[$id] = $entity;
        $this->eventManager->emit(new EntityCreatedMessage($entity));

        return $entity;
    }

    /**
     * @param int $id Entity ID
     * @return Entity
     * @throws \Exception
     */
    public function getEntity($id) {
        if (isset($this->entities[$id])) {
            return $this->entities[$id];
        }

        throw new \Exception(sprintf('Entity with ID "%s" not found.', $id));
    }

    /**
     * @param string|string[] $classNames
     * @return Entity[]
     */
    public function getEntitiesByAllComponents($classNames) {
        if (is_string($classNames)) {
            $classNames = [$classNames];
        }

        $entities = [];
        foreach ($this->entities as $entity) {
            if ($entity->hasAllComponents($classNames)) {
                $entities[] = $entity;
            }
        }

        return $entities;
    }

    public function getEntitiesByAnyComponent($classNames) {
        if (is_string($classNames)) {
            $classNames = [$classNames];
        }

        $entities = [];
        foreach ($this->entities as $entity) {
            if ($entity->hasAnyComponent($classNames)) {
                $entities[] = $entity;
            }
        }

        return $entities;
    }

    /**
     * @param int $id Entity ID
     * @throws \Exception
     */
    public function destroyEntity($id) {
        $entity = $this->getEntity($id);
        $entity->destroy();
        unset($this->entities[$id]);
        $this->entityFreeIds[] = $id;
        $this->eventManager->emit(new EntityDestroyedMessage($entity));
    }

    /**
     * @return int
     */
    private function fetchNewId() {
        if (count($this->entityFreeIds)) {
            $id = array_shift($this->entityFreeIds);
        } else {
            $id = ++static::$entityCount;
        }

        return $id;
    }
}
