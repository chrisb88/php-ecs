<?php

namespace ecs\events\messages;

use ecs\Entity;

class EntityDestroyedMessage extends Message
{
    /**
     * @var Entity
     */
    private $entity;

    /**
     * @param Entity $entity
     */
    public function __construct(Entity $entity) {
        $this->entity = $entity;
    }
}
