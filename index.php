<?php
require_once 'vendor/autoload.php';

if(file_exists('config.php')) {
    require_once 'config.php';
} else {
    require_once 'config.distro.php';
}

$context = array(
    'ssl' => array(
        'allow_self_signed' => true
    )
);

$client = new \Pixi\API\Soap\Client(null, array(
    "trace"             => 1,
    "login"             => $username,
    "password"          => $password,
    "location"          => $location,
    "uri"               => $uri,
    'user_agent'        => 'pixi API Client 0.1',
    'soap_version'      => SOAP_1_2,
    'ssl_method'        => SOAP_SSL_METHOD_TLS,
    'stream_context'    => $context
));

try {
    
    $rs = $client->pixiGetShops()->getResultset();
    
    print_r($rs);
    
} catch (\SoapFault $s) {
    
    echo $s->getMessage();
    
    print_r($client->__getLastRequest());
    
} catch (Exception $e) {
    
    print_r($e);
    echo $e->getMessage() . "\n\n";
    print_r($client->__getLastRequestHeaders());
    echo "\n\n";
    print_r($client->__getLastRequest());
    echo "\n\n";
    print_r($client->__getLastResponseHeaders());
    echo "\n\n";
    print_r($client->__getLastResponse());
    
}
