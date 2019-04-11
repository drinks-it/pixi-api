<?php

require __DIR__.'/../vendor/autoload.php';

$username = 'pixiAPP';
$password = 'fHNzq44NA6kaDm_APP';
$endpoint = "https://soap.pixi.eu/soap/$username/";

$options = new Pixi\API\Soap\Options($username, $password, $endpoint);
$options->allowSelfSigned();

$soapClient = new \Pixi\API\Soap\Client(null, $options->getOptions());

$curlTransport = new \Pixi\API\Soap\Transport\CurlTransport();
$soapClient->setTransportObject($curlTransport);
$soapClient->setResultObject('\Pixi\API\Soap\Result\ArrayResult');

$resultArray = $soapClient->pixiGetShops()->getResultset();

echo '<pre>';
var_dump($resultArray);
