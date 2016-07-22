<?php

namespace Pixi\API\Soap\Result;

interface ResultInterface
{
    
    public function getResultSet();
    
    public function setResultSet($result);
    
    public function  setIgnoreErrors($bool);
    
}
