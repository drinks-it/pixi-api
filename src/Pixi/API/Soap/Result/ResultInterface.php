<?php

namespace Pixi\API\Soap\Result;

/**
 * Interface ResultInterface
 * @package Pixi\API\Soap\Result
 */
interface ResultInterface
{
    /**
     * @return array
     */
    public function getResultSet();

    /**
     * @param $result
     * @return mixed
     */
    public function setResultSet($result);

    /**
     * @param $bool
     * @return mixed
     */
    public function  setIgnoreErrors($bool);
    
}
