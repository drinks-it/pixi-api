<?php

namespace Pixi\API\Soap\Exception;

class PixiApiException extends \Exception 
{
    public function __construct($message, $code = 0) 
    {
        parent::__construct($message, $code);
    }
} 