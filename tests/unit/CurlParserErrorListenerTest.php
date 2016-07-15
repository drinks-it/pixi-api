<?php

use Pixi\API\Soap\Client;
use Pixi\API\Soap\Options;
use Pixi\API\Soap\Result\ArrayResult;
use Pixi\API\Soap\Transport\CurlTransport;

class CurlParserErrorListenerTest extends \PHPUnit_Framework_TestCase
{

    private $options = array(
        "trace"          => 1,
        "login"          => 'pixiAPP',
        "password"       => 'fHNzq44NA6kaDm_APP',
        "location"       => 'https://virgo3.api.pixi.eu/pixiAPP/',
        "uri"            => 'https://virgo3.api.pixi.eu/pixiAPP/',
        'user_agent'     => 'pixi API Client 0.1',
        'soap_version'   => SOAP_1_2,
        'stream_context' => [
            'ssl' => [
                'allow_self_signed' => true,
                'verify_peer'       => false,
                'verify_peer_name'  => false,
            ],
        ],
    );

    /**
     * @expectedException \Pixi\API\Soap\Result\ResultException
     */
    public function testClassWithProcess()
    {
        $client = new Client(null, $this->options);
        $transport = new CurlTransport();
        $client->setTransportObject($transport);

        $client->setResultObject('\Pixi\API\Soap\Result\ArrayResult');
        $client->pixiGetShops(['silvester' => 500])->getResultset();
    }
}
