<?php

class Hermes_Listener_GithubTest extends PHPUnit_Framework_TestCase
{
    public function testPulls()
    {
        include __DIR__ . "/files/Github/credentials.php";
        $apiKey = '';
        $subdomain = '';
        $hermes = new Hermes_Bot($apiKey, $subdomain, new Zend_Http_Client());
        $listener = new Hermes_Listener_Github($githubApiKey, $githubUser, new Zend_Http_Client());
        $response = $listener->pulls($hermes, '');
        var_dump($response);exit;
        $this->assertEquals($response, '');
    }
}
