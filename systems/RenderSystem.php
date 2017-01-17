<?php

namespace ecs\systems;

use ecs\components\Description;
use ecs\components\Name;

class RenderSystem extends System implements SystemInterface
{
    public function init() {
//        $this->eventManager->subscribeMbox(ComponentAdded::class);
    }

    public function update() {
        $entities = $this->entityManager->getEntitiesByAnyComponent(Description::class);
        var_dump($entities);

        $entities = $this->entityManager->getEntitiesByAllComponents([Description::class, Name::class]);
        var_dump($entities);
    }
}
