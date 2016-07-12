<?php

namespace Pixi\API\Soap\Tests;

use Pixi\API\Soap\Client;
use Pixi\API\Soap\Options;

class ClientTest extends \PHPUnit_Framework_TestCase
{
    private $client;
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

    private $expectedResult = array (
            array (
                'ShopID' => 'FLO',
                'ShopName' => 'FLO',
                'Country' => 'D',
            ),
            array (
                'ShopID' => 'STG',
                'ShopName' => 'Hier Webadresse des Shops eintragen!',
                'Address' => 'Hier Strasse und Hausnummer eintragen!',
                'City' => 'Hier Ort eintragen!',
                'State' => 'GER',
                'ZIP' => 'PLZ',
                'Country' => 'D  ',
                'Contact' => 'Hier Ansprechpartner eintragen!',
                'Phone' => 'Hier Telefonnummer von Ansprechpartner eintragen!',
                'Fax' => 'Hier Faxnummer eintragen',
                'ShopCompany' => 'Firma',
                'eMail' => 'installation@pixi.eu',
                'BIC' => 'UstID und Steuernummer',
                'IBAN' => 'IBAN und SWIFT',
                'KontoBLZ' => 'Kontonummer und BLZ',
                'BankName' => 'Bankname',
            ),
            array (
                'ShopID' => 'URO',
                'ShopName' => 'URO',
                'Country' => 'D',
            ),
    );


    protected function setUp()
    {
    }

    protected function tearDown()
    {
    }

    public function testConstruct()
    {

        $client = new Client(null, $this->options);

        $this->assertSame($this->options, $client->clientOptions);
    }

    public function testSetPixiOptions()
    {

        $expected = array(
            'user_agent'   => 'pixi API Client 0.1',
            'soap_version' => SOAP_1_2,
            "login"        => 'username',
            "password"     => 'password',
            "uri"          => 'uri',
            "location"     => 'uri',
            "trace"        => 1,
        );

        $options = new Options('test', 'test', 'test');

        $options->setOptions(array(
            "trace"        => 1,
            "login"        => 'username',
            "password"     => 'password',
            "location"     => 'uri',
            "uri"          => 'uri',
            'user_agent'   => 'pixi API Client 0.1',
            'soap_version' => SOAP_1_2,
        ));

        $client = new Client();
        $client->setPixiOptions($options);

        $this->assertSame($expected, $client->clientOptions);
    }

    public function testCallMethod()
    {
        $client = new Client(null, $this->options);

        $rs = $client->pixiGetShops()->getResultSet();

        $this->assertSame($this->expectedResult, $rs);
    }

    public function testCallMethodWithArguments()
    {
        $client = new Client(null, $this->options);

        $rs = $client->pixiGetShops(array('ShopID' => 'FLO'))->getResultSet();

        $this->assertSame(array($this->expectedResult[0]), $rs);
    }

    /**
     * @expectedException Exception
     */
    public function testCallMethodWithoutPixiName()
    {
        $client = new Client(null, $this->options);

        $rs = $client->GetShops()->getResultSet();
    }
}
