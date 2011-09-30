<?php

class Hermes_Bot
{

    public $apiKey = null;
    public $subdomain = null;
    public $events = array();

    /**
     *
     * @var Zend_Http_Client
     */
    public $client = null;

    public function __construct($apiKey, $subdomain, Zend_Http_Client $client)
    {
        $this->apiKey = $apiKey;
        $this->client = $client;
        $this->subdomain = $subdomain;
        $this->client->setAuth($this->apiKey,'X');
    }

    public function joinRoom($room)
    {
        $uri = 'https://'.$this->subdomain.'.campfirenow.com/room/'.$room.'/join.json';
        $this->client->setHeaders('Content-type','application/json');
        $this->client->setUri($uri);
        $this->client->setMethod(Zend_Http_Client::POST);
        $response = $this->client->request();

        return (bool)($response->getStatus() == 200);
    }

    public function leaveRoom($room)
    {
        $uri = 'https://'.$this->subdomain.'.campfirenow.com/room/'.$room.'/leave.json';
        $this->client->setHeaders('Content-type','application/json');
        $this->client->setUri($uri);
        $this->client->setMethod(Zend_Http_Client::POST);
        $response = $this->client->request();

        return (bool)($response->getStatus() == 200);
    }

    public function listenRoom($room)
    {
        $this->joinRoom($room);
        $uri = 'https://'.$this->subdomain.'.campfirenow.com/room/'.$room.'/recent.json';
        $lastMessageId = null;
        while(true){
            $this->client->resetParameters();
            if (!is_null($lastMessageId)) {
                $this->client->setParameterGet('since_message_id',$lastMessageId);
            } else {
                $this->client->setParameterGet('limit', 1);
            }
            $this->client->setUri($uri);
            $this->client->setMethod(Zend_Http_Client::GET);
            $response = $this->client->request();
            $newMessageId = $this->processMessages($response);
            if ($newMessageId > $lastMessageId) {
                $lastMessageId = $newMessageId;
            }
            sleep(1);
        }

        return (bool)($response->getStatus() == 200);
    }

    public function sayRoom($room, $message, $type='TextMessage')
    {
        $uri = 'https://'.$this->subdomain.'.campfirenow.com/room/'.$room.'/speak.json';
        $this->client->setUri($uri);

        $params['message']['type'] = $type;
        $params['message']['body'] = $message;

        $this->client->setHeaders('Content-type','application/json');
        $this->client->setRawData(json_encode($params));
        $this->client->setMethod(Zend_Http_Client::POST);
        $response = $this->client->request();

        return (bool)($response->getStatus() == 200);
    }

    protected function processMessages(Zend_Http_Response $response)
    {
        $lastMessageId = 0;
        $messages = json_decode($response->getBody(), true);
        if (!$messages) {
            return $lastMessageId;
        }
        foreach ($messages['messages'] as $message){
            if ($message) {
                $this->triggerEvents($message);
                $lastMessageId = $message['id'];
            }
        }

        return $lastMessageId;
    }

    public function attachEvent($event, $callback)
    {
        $this->events[$event][]=$callback;
    }

    public function triggerEvents($message)
    {
        $matches = array();
        preg_match('/^@?hermes\b.*?(?<command>[[:alpha:]]+)/i', $message['body'], $matches);
        if (isset($matches['command'])) {
            $command = $matches['command'];
            if (isset($this->events[$command])) {
                foreach ($this->events[$command] as $callback) {
                    call_user_func($callback, $this, $message);
                }
            }
        }
    }
}