<?php

class Hermes_Listener_Github extends Hermes_Listener_Abstract
{
    public $events = array();
    public $githubApiKey = null;
    public $githubUser = null;

    /**
     *
     * @var Zend_Http_Client
     */
    public $client = null;


    public function __construct($githubApiKey, $githubUser, Zend_Http_Client $client)
    {
        $this->githubApiKey = $githubApiKey;
        $this->githubUser = $githubUser;
        $this->client = $client;
    }

    public function pulls(Hermes_Bot $hermes, $params)
    {
        $repo = 'deseretdigital/framework';
        $this->client->setAuth($this->githubUser . "/token", $this->githubApiKey);
        $this->client->setUri('http://github.com/api/v2/json/pulls/' . $repo);
        $response = $this->client->request()->getBody();
        return $response;
    }
}
