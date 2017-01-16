<?php

namespace ecs;

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
     * @var Component[]
     */
    private $components;

    /**
     * @param EntityManager $entityManager
     * @param int $id
     */
    public function __construct(EntityManager &$entityManager, $id) {
        $this->id = $id;
        $this->components = [];
        $this->entityManager = $entityManager;
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
     */
    public function addComponent(Component $component) {
        $this->components[] = $component;
    }

    public function removeComponent() {

    }

    public function hasComponent() {

    }
}
