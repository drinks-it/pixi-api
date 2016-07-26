<?php
namespace Pixi\API\Soap\Transport;

class TransportException extends \Exception
{
    
    public $transportObject;
    
    public function __construct($message, $code, $transportObject = null)
    {
        
        if($transportObject) {
            $this->setTransportObject($transportObject);
        }
        
        parent::__construct($message, $code);
        
    }
    
    public function setTransportObject($transportObject)
    {
        $this->transportObject = $transportObject;
    }
}
