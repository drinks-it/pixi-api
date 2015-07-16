<?php

// Including the autoloader and an Subscriber
require __DIR__.'/../vendor/autoload.php';

$username = 'pixiAPP';
$password = 'fHNzq44NA6kaDm_APP';
$endpoint = 'https://leonidas.api.madgeniuses.net/pixiAPP/';

$options = new Pixi\API\Soap\Options($username, $password, $endpoint);

$options->allowSelfSigned();

/*
$options->setStreamContextOptions(array(
    'ssl' => array(
        'allow_self_signed' => true,
    )
));
*/

$soapClient = new \Pixi\API\Soap\Client(null, $options->getOptions());

echo $soapClient->getRevision();

print_r($soapClient->pixiGetShops()->getResultSet());