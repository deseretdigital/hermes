<?

class BotTest extends PHPUnit_Framework_TestCase
{
    public function testJoin()
    {
        include __DIR__ . '/files/credentials.php';
        $bot = new Hermes_Bot($apiKey, $subdomain, new Zend_Http_Client());
        $bot->joinRoom($room);
    }

    public function testListen()
    {
        include __DIR__ . '/files/credentials.php';
        $bot = new Hermes_Bot($apiKey, $subdomain, new Zend_Http_Client());
        $ping = new Hermes_Listener_Ping($bot, $pingIniFile, 'testing');
        $ping = new Hermes_Listener_Deploy($bot);
        $bot->listenRoom($room);
    }
    public function testLeave()
    {
        include __DIR__ . '/files/credentials.php';
        $bot = new Hermes_Bot($apiKey, $subdomain, new Zend_Http_Client());
        $bot->leaveRoom($room);
    }
}
