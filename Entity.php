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
    public function __construct(EntityManager $entityManager, $id) {
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

    /**
     * @param string $className
     * @return bool
     * @todo refactor so we don't need to iterate over all components und get it's class
     */
    public function hasComponent($className) {
        foreach ($this->components as $component) {
            if (get_class($component) == $className) {
                return true;
            }
        }

        return false;
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
