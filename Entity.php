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
     * @var string[]
     */
    private $componentClassNames;

    /**
     * @param EntityManager $entityManager
     * @param int $id
     */
    public function __construct(EntityManager $entityManager, $id) {
        $this->id = $id;
        $this->components = [];
        $this->componentClassNames = [];
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
     * @return $this
     */
    public function addComponent(Component $component) {
        $this->components[] = $component;
        $className = get_class($component);
        if (!in_array($className, $this->componentClassNames)) {
            $this->componentClassNames[] = $className;
        }

        return $this;
    }

    public function removeComponent() {
        throw new \Exception("Not implemented.");
    }

    /**
     * @param string $className
     * @return bool
     */
    public function hasComponent($className) {
        return in_array($className, $this->componentClassNames);
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
