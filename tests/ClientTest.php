<?php

use PHPUnit\Framework\TestCase;
use Pixi\API\Soap\Client;
use Pixi\API\Soap\Result\ArrayResult;
use Pixi\API\Soap\Transport\CurlTransport;

class ClientTest extends TestCase
{
    private $options = array(
        "trace"          => 1,
        "login"          => 'pixiAPP',
        "password"       => 'fHNzq44NA6kaDm_APP',
        "location"       => 'https://soap.pixi.eu/soap/pixiAPP/',
        "uri"            => 'https://soap.pixi.eu/soap/pixiAPP/',
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

    private $expectedResult = array(
        array(
            'ShopID'   => 'FLO',
            'ShopName' => 'FLO',
            'Address' => 'Am Umspannwerk 8',
            'City' => 'Sottrum',
            'ZIP' => '27367',
            'Country'  => 'D  ',
            'Contact' => 'email@email.com',
            'Phone' => '861111111111',
            'eMail' => 'errors@errors.com',
            'CostCenter' => '000002',
        ),
        array(
            'ShopID'      => 'STG',
            'ShopName'    => 'STG',
            'Address'     => 'Am Umspannwerk 8',
            'City'        => 'Sottrum',
            'State'       => 'GER',
            'ZIP'         => '27367',
            'Country'     => 'D  ',
            'Contact'     => 'email@email.com',
            'Phone'       => '861111111111',
            'ShopCompany' => 'Firma',
            'eMail'       => 'installation@pixi.eu',
            'BIC'         => 'UstID und Steuernummer',
            'IBAN'        => 'IBAN und SWIFT',
            'KontoBLZ'    => 'Kontonummer und BLZ',
            'BankName'    => 'Bankname',
            'CostCenter' => '000001',
        ),
        array(
            'ShopID'   => 'URO',
            'ShopName' => 'URO',
            'Address' => 'Am Umspannwerk 8',
            'City' => 'Sottrum',
            'ZIP' => '27367',
            'Country' => 'D  ',
            'Contact' => 'email@email.com',
            'Phone' => '681111111111',
            'eMail' => 'errors@errors.com',
            'CostCenter' => '000001',
        ),
    );

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

        $options = new \Pixi\API\Soap\Options('test', 'test', 'test');

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

    public function testCallMethodWithoutPixiName()
    {
        $this->expectException(SoapFault::class);

        $client = new Client(null, $this->options);

        $rs = $client->GetShops()->getResultSet();
    }

    public function testClientWithCurlTransportStringDefinition()
    {
        return $this->markTestSkipped();

        $client = new Client(null, $this->options);
        $client->setTransportObject('\Pixi\API\Soap\Transport\CurlTransport');

        $resultObject = new ArrayResult();
        $client->setResultObject($resultObject);

        $rs = $client->pixiGetShops()->getResultSet();
        $this->assertSame($this->expectedResult, $rs);
    }

    public function testClientWithCurlTransportObjectDefinition()
    {
        return $this->markTestSkipped();

        $client = new Client(null, $this->options);
        $transport = new CurlTransport();
        $client->setTransportObject($transport);

        $resultObject = new ArrayResult();
        $client->setResultObject($resultObject);

        $rs = $client->pixiGetShops()->getResultSet();
        $this->assertSame($this->expectedResult, $rs);
    }

    public function testClientWithCurlTrasnportObjectThrowingTransportException()
    {
        return $this->markTestSkipped();

        $this->expectException(\Pixi\API\Soap\Transport\TransportException::class);

        $client = new Client(null, array_merge($this->options, array(
            "location" => 'https://soap_nonexisting.pixi.eu/soap/pixiAPP/',
            "uri"      => 'https://soap_nonexisting.pixi.eu/soap/pixiAPP/',
        )));
        $client->setTransportObject('\Pixi\API\Soap\Transport\CurlTransport');

        $resultObject = new ArrayResult();
        $client->setResultObject($resultObject);

        $client->pixiGetShops()->getResultSet();
    }

    public function testClientWithCurlTrasnportObjectThrowingCurlSoapFault()
    {
        return $this->markTestSkipped();

        $this->expectException(SoapFault::class);

        $client = new Client(null, $this->options);
        $client->setTransportObject('\Pixi\API\Soap\Transport\CurlTransport');

        $resultObject = new ArrayResult();
        $client->setResultObject($resultObject);

        $client->ApiCallWhichDoesNotExist()->getResultSet();
    }

    public function testClientWithCurlTrasnportObjectThrowingResultException()
    {
        return $this->markTestSkipped();

        $this->expectException(\Pixi\API\Soap\Result\ResultException::class);

        $client = new Client(null, $this->options);
        $transport = new CurlTransport();
        $client->setTransportObject($transport);

        $client->setResultObject('\Pixi\API\Soap\Result\ArrayResult');
        $client->pixiGetShops(['silvester' => 500])->getResultset();
    }
}
