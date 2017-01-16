<?php

require_once('EntityManager.php');
require_once('EventManager.php');
require_once('components/Description.php');
require_once('messages/EntityCreatedMessage.php');
require_once('events/Receiver.php');

use ecs\components\Description;
use ecs\events\messages\EntityCreatedMessage;

$rcv = new ecs\events\Receiver(function(\ecs\events\messages\Message $message) {
    var_dump($message->getEntity()->getId());
});
$eventManager = new \ecs\events\EventManager();
$eventManager->subscribe($rcv, EntityCreatedMessage::class);

$eManager = new \ecs\EntityManager($eventManager);
$entity = $eManager->createEntity();
$entity2 = $eManager->createEntity();
$entity->addComponent(new Description("Hello World!"));

//var_dump($entity);
