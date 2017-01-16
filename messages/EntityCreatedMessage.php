<?php

namespace ecs\events\messages;

use ecs\Entity;

require_once('events/Message.php');

class EntityCreatedMessage extends Message
{
    /**
     * @var Entity
     */
    private $entity;

    /**
     * @param Entity $entity
     */
    public function __construct(Entity &$entity) {
        $this->entity = $entity;
    }

    public function getEntity() {
        return $this->entity;
    }
}
