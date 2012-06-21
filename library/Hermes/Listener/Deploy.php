<?php

class Hermes_Listener_Deploy extends Hermes_Listener_Abstract
{
    public $events = array();

    public function __construct(Hermes_Bot $hermes, $filename = '', $listenerLevel = null)
    {
        $this->events[]=array('action' => 'deploy', 'method' => array($this, 'deploy'));
        parent::__construct($hermes, $filename, $listenerLevel);
    }

    public function deploy(Hermes_Bot $hermes, $params)
    {
        $match = array();
        $room = $params['room_id'];
        if (preg_match('/deploy (?<repo>.*)/',$params['body'], $match)) {
            $repo = $match['repo'];
            $hermes->sayRoom($room, 'Deploying ' . $repo);
            $repoArg = escapeshellarg($repo);
            $returnCode = exec("cd /var/www/deploy/data/$repoArg && sudo -u hhatfield /opt/local/bin/cap deploy");
            if ($returnCode != 0) {
                return $hermes->sayRoom($room, 'Deployed ' . $repo);
            } else {
                return $hermes->sayRoom($room, 'Error Deploying ' . $repo);
            }
        } else {
            return $hermes->sayRoom($room, 'Did not grok deploy message');
        }

    }
}
