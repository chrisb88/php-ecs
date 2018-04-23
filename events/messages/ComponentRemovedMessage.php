<?php

namespace ecs\events\messages;

use ecs\Entity;

class ComponentRemovedMessage extends Message
{
    /**
     * @var string
     */
    private $className;

    /**
     * @var Entity
     */
    private $entity;

    /**
     * @param string $className
     * @param Entity $entity
     */
    public function __construct(string $className, Entity $entity)
    {
        $this->className = $className;
        $this->entity = $entity;
    }

    public function getName()
    {
        return $this->className;
    }

    public function getEntity()
    {
        return $this->entity;
    }
}
