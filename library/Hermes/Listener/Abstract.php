<?php

class Hermes_Listener_Abstract implements Hermes_Listener
{
    public $hermes = null;
    public $events = array();

    /**
     *
     * Enter description here ...
     * @var Zend_Config_Ini
     */
    public $config = null;

    public function __construct(Hermes_Bot $hermes, $filename = '', $listenerLevel = null)
    {
        $this->hermes = $hermes;
        $this->register($this->events);
        if (is_null($listenerLevel)) {
            $listenerLevel = $this->hermes->getApplicationLevel();
        }
        if( $filename) {
            $this->loadConfig($filename, $listenerLevel);
        }
    }

    public function register($events)
    {
        foreach ($events as $event) {
            $this->hermes->attachEvent($event['action'],$event['method']);
        }
    }

    public function loadConfig( $filename, $listenerLevel )
    {
        $ini = new Zend_Config_Ini( $filename, $listenerLevel );
        $this->config = $ini->toArray();
    }
}
