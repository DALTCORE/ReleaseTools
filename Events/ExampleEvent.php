<?php

namespace DALTCORE\ReleaseTools\Events;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventDispatcher;

class ExampleEvent
{
    /**
     * @param \Symfony\Component\EventDispatcher\Event           $event
     * @param                                                    $name
     * @param \Symfony\Component\EventDispatcher\EventDispatcher $eventDispatcher
     */
    public function begin(Event $event, $name, EventDispatcher $eventDispatcher)
    {
        //
    }
}
