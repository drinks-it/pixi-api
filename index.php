<?php
use Pixi\API\Soap\ResultInterface;

$_GET['XDEBUG_PROFILE'] = true;

require_once 'vendor/autoload.php';

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
     * @see \Pixi\API\Soap\ResultInterface::__construct()
     */
    public function __construct($result)
    {
        // TODO: Auto-generated method stub
        $this->rs = $result;
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
    
}

try {
    
    $client->setResultObject('ReturnFake');
    
    // $rs = $client->pixiGetShops()->getResultset();
    $rs = $client->pixiGetShops()->getResultset();
    
    print_r($rs);
    
    echo count($rs);
} catch (\SoapFault $s) {
    
    echo $s->getMessage();
    
    print_r($client->__getLastRequest());
    
    print_r($e);
    echo $e->getMessage() . "\n\n";
    print_r($client->__getLastRequestHeaders());
    echo "\n\n";
    print_r($client->__getLastRequest());
    echo "\n\n";
    print_r($client->__getLastResponseHeaders());
    echo "\n\n";
    print_r($client->__getLastResponse());
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
