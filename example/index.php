<?php

// Including the autoloader and an Subscriber
require __DIR__.'/../vendor/autoload.php';

$username = 'pixiAPP';
$password = 'app030211!';
$endpoint = 'https://leonidas.api.madgeniuses.net/pixiAPP/';

$options = new Pixi\API\Soap\Options($username, $password, $endpoint);
$soapClient = new \Pixi\API\Soap\Client(null, $options->getOptions());

echo $soapClient->getRevision();
print_r($soapClient->pixiGetShops()->getResultSet());