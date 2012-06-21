<?php

class Hermes_Listener_Ping extends Hermes_Listener_Abstract
{
    public $events = array();

    public function __construct(Hermes_Bot $hermes, $filename, $listenerLevel = null)
    {
        $this->events[]=array('action' => 'ping', 'method' => array($this, 'ping'));
        parent::__construct($hermes, $filename, $listenerLevel);
    }

    public function ping(Hermes_Bot $hermes, $params)
    {
        $prefix = $this->config['listener']['ping']['prefix'];
        $room = $params['room_id'];
        return $hermes->sayRoom($room, $prefix . ' Pong');
    }
}
