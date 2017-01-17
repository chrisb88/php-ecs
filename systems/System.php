<?php

namespace ecs\systems;

use ecs\EntityManager;
use ecs\events\EventManager;

abstract class System
{
    /**
     * @var EventManager
     */
    protected $eventManager;

    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @param EventManager $eventManager
     * @param EntityManager $entityManager
     */
    public function __construct(EventManager $eventManager, EntityManager $entityManager) {
        $this->eventManager = $eventManager;
        $this->entityManager = $entityManager;
        $this->init();
    }

    public function init() {}
}
