<?php

require_once 'vendor/autoload.php';

if(file_exists('config.php')) {
	require_once 'config.php';
} else {
	require_once 'config.distro.php';
}

$client = new \Pixi\API\Soap\Client(
	null,
	array(
		"trace" => 1, 
		"login" => $username, 
		"password" => $password,
		"location" => $uri,
		"uri" => $uri,
	    'user_agent' => 'pixi API Client 0.1',
	    'soap_version' => SOAP_1_2
	)
); 

try {
    
	//$rs = $client->pixiGetOrderline(array('RowCount' => 10))->getResultset();
	//$rs = $client->pixiGetOrderline()->getResultset();
	//$rs = $client->pixiGetShops();
	//$rs = $client->pixiGetShops(array('ShopID' => 'MAD'));
	$rs = $client->pixiGetShops(array('ShopID' => 'MAD'))->getResultset();

	print_r($rs);
	
} catch (\SoapFault $s) {
    
    echo $s->getMessage();
    
    print_r($client->__getLastRequest());
	
} catch(Exception $e) {
    
    print_r($e);
	echo $e->getMessage()."\n\n";
	print_r($client->__getLastRequestHeaders());
	echo "\n\n";
	print_r($client->__getLastRequest());
	echo "\n\n";
	print_r($client->__getLastResponseHeaders());
	echo "\n\n";
	print_r($client->__getLastResponse());

}
