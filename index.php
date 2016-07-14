<?php
require_once 'vendor/autoload.php';

use Pixi\API\Soap\Result\ResultInterface;
use Pixi\API\Soap\Transport\CurlTransport;
use Pixi\API\Soap\Transport\TransportException;
use Pixi\API\Soap\Result\ResultException;

$_GET['XDEBUG_PROFILE'] = true;

if (file_exists('config.php')) {
    require_once 'config.php';
} else {
    require_once 'config.distro.php';
}

$context = array(
    'ssl' => array(
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => true
    )
);

$client = new \Pixi\API\Soap\Client(null, array(
    "trace" => true,
    "login" => $username,
    "password" => $password,
    "location" => $location,
    "uri" => $uri,
    'user_agent' => 'pixi API Client 0.1',
    'soap_version' => SOAP_1_2,
    'ssl_method' => SOAP_SSL_METHOD_TLS,
    'stream_context' => $context
));

class ReturnFake implements ResultInterface
{

    public $rs;
    
    /**
     *
     * {@inheritDoc}
     *
     * @see \Pixi\API\Soap\ResultInterface::getResultSet()
     */
    public function getResultSet()
    {
        // TODO: Auto-generated method stub
        return $this->rs;
    }


    /**
     *
     * {@inheritDoc}
     *
     * @see \Pixi\API\Soap\ResultInterface::setIgnoreErrors()
     */
    public function setIgnoreErrors($bool)
    {
        // TODO: Auto-generated method stub
    }

    public function setResultSet($result)
    {
        $this->rs = $result;
    }
}

try {
    
    $transport = new CurlTransport();
    $client->setTransportObject($transport);
    
//    $client->setResultObject('ReturnFake');
    $client->setResultObject('\Pixi\API\Soap\Result\ArrayResult');
    
    // $rs = $client->pixiGetShops()->getResultset();
//    $client->getResultObject()->setIgnoreErrors(true);
    
    $rs = $client->pixiGetShopss(['silvester' => 500])->getResultset();

    print_r($rs);
    
} catch(TransportException $e) {
    
    print_r($e);
    
} catch(ResultException $e) {

    print_r($e);
    
} catch (\SoapFault $e) {

    print_r($e);
    
    /*
    print_r($e);
    echo $e->getMessage() . "\n\n";
    print_r($client->__getLastRequestHeaders());
    echo "\n\n";
    print_r($client->__getLastRequest());
    echo "\n\n";
    print_r($client->__getLastResponseHeaders());
    echo "\n\n";
    print_r($client->__getLastResponse());
    */
    
} catch (Exception $e) {
    
    print_r($e);
    //echo $e->getMessage() . "\n\n";
    /*
    print_r($client->__getLastRequestHeaders());
    echo "\n\n";
    print_r($client->__getLastRequest());
    echo "\n\n";
    print_r($client->__getLastResponseHeaders());
    echo "\n\n";
    print_r($client->__getLastResponse());
    */
    
}
