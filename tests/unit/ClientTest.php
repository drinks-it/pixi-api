<?php

namespace Pixi\API\Soap\Tests;

use Pixi\API\Soap\Client;
use Pixi\API\Soap\Options;

class ClientTest extends \PHPUnit_Framework_TestCase
{
    private $client;

    protected function setUp()
    {
    }

    protected function tearDown()
    {
    }

    public function testConstruct()
    {
        $options = array(
            "trace" => 1,
            "login" => 'username',
            "password" => 'password',
            "location" => 'uri',
            "uri" => 'uri',
            'user_agent' => 'pixi API Client 0.1',
            'soap_version' => SOAP_1_2,
            'stream_context' => [
                'ssl' => [
                    'allow_self_signed' => true,
                    'verify_peer'       => false,
                    'verify_peer_name'  => false,
                ],
            ],
        );

        $client = new Client(null, $options);

        $this->assertSame($options, $client->clientOptions);
    }

    public function testSetPixiOptions()
    {

        $expected = array(
            'user_agent' => 'pixi API Client 0.1',
            'soap_version'  => SOAP_1_2,
            "login" => 'username',
            "password" => 'password',
            "uri" => 'uri',
            "location" => 'uri',
            "trace" => 1,
        );

        $options = new Options('test', 'test', 'test');

        $options->setOptions(array(
                "trace" => 1,
        		"login" => 'username',
        		"password" => 'password',
        		"location" => 'uri',
        		"uri" => 'uri',
        	    'user_agent' => 'pixi API Client 0.1',
        	    'soap_version' => SOAP_1_2
        ));

        $client = new Client();
        $client->setPixiOptions($options);

        $this->assertSame($expected, $client->clientOptions);
    }
}
