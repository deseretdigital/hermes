<?php

class Hermes_Listener_Abstract implements Hermes_Listener
{
    public $hermes = null;
    public $events = array();

    public function __construct(Hermes_Bot $hermes)
    {
        $this->hermes = $hermes;
        $this->register($this->events);
    }

    public function register($events)
    {
        foreach ($events as $event) {
            $this->hermes->attachEvent($event['action'],$event['method']);
        }
    }
}
