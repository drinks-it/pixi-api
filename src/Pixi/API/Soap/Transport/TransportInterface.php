<?php

namespace Pixi\API\Soap\Transport;

interface TransportInterface
{
    
    public function __doRequest($request, $location, $action, $version);
    
    public function setOptions($options);
    
}
