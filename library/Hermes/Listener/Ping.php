<?php

class Hermes_Listener_Ping extends Hermes_Listener_Abstract
{
    public $events = array();

    public function __construct(Hermes_Bot $hermes)
    {
        $this->events[]=array('action' => 'ping', 'method' => array($this, 'ping'));
        parent::__construct($hermes);
    }

    public function ping(Hermes_Bot $hermes, $params)
    {
        $room = $params['room_id'];
        return $hermes->sayRoom($room,'Pong');
    }
}
