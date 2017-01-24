<?php

namespace ecs\systems;

use ecs\components\Description;
use ecs\components\Name;
use ecs\events\messages\ComponentAddedMessage;
use ecs\events\messages\EntityCreatedMessage;
use ecs\events\messages\Message;

class RenderSystem extends System implements SystemInterface
{
    public function init() {
        $this->subscribeMbox(ComponentAddedMessage::class);
        $this->subscribeMbox(EntityCreatedMessage::class);
    }

    public function update() {
//        $entities = $this->entityManager->getEntitiesByAnyComponent(Description::class);
//        var_dump(static::class, $entities);
//
//        $entities = $this->entityManager->getEntitiesByAllComponents([Description::class, Name::class]);
//        var_dump($entities);

//        var_dump($this->mailboxes);
        $mb = $this->getMailbox(ComponentAddedMessage::class);
        while (!$mb->isEmpty()) {
            /* @var ComponentAddedMessage $msg */
            $msg = $mb->dequeue();
            var_dump($msg->getComponent());
        }

        $this->eachMbox(EntityCreatedMessage::class, function(Message $msg) {
            /* @var EntityCreatedMessage $msg */
            var_dump($msg->getEntity()->getId());
        });
    }
}
