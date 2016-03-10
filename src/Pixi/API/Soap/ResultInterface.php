<?php

namespace Pixi\API\Soap;

interface ResultInterface
{
    
    public function __construct($result);
    
    public function getResultSet();
    
    public function  setIgnoreErrors($bool);
    
}
