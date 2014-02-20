<?php

namespace Pixi\API\Soap\Tests;

use Pixi\API\Soap\Client;
use Pixi\API\Soap\Options;

class ClientTest extends \PHPUnit_Framework_TestCase
{
    private $client;

    protected function setUp()
    {
        $this->client = new Client();
    }

    protected function tearDown()
    {
        $this->client = null;
    }

    public function testConstruct()
    {
        $client = new Client(null, array(
            "trace" => 1,
    		"login" => 'username',
    		"password" => 'password',
    		"location" => 'uri',
    		"uri" => 'uri',
    	    'user_agent' => 'pixi API Client 0.1',
    	    'soap_version' => SOAP_1_2
        ));

        $this->assertSame('username', $client->_login);
    }

    public function testSetPixiOptions()
    {
        $options = new Options('string', 'string', 'string');
        $options->setOptions(
            array(
                "trace" => 1,
        		"login" => 'username',
        		"password" => 'password',
        		"location" => 'uri',
        		"uri" => 'uri',
        	    'user_agent' => 'pixi API Client 0.1',
        	    'soap_version' => SOAP_1_2
            )
        );

        $this->client->setPixiOptions($options);
        $this->assertSame('username', $this->client->_login);
        $this->assertSame(1, $this->client->trace);
    }
}