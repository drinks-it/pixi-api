<?php
namespace Pixi\API\Soap\Result;

class ArrayResult implements ResultInterface
{

    public $resultSet;
    
    /**
     *
     * {@inheritDoc}
     *
     * @see \Pixi\API\Soap\Result\ResultInterface::getResultSet()
     */
    public function getResultSet()
    {
        return $this->resultSet;
    }

    /**
     *
     * {@inheritDoc}
     *
     * @see \Pixi\API\Soap\Result\ResultInterface::setIgnoreErrors()
     */
    public function setIgnoreErrors($bool)
    {
        return $this;
    }
    

    public function setResultSet($result)
    {
        if(is_array($result)) {
            $this->resultSet = $result;
        }
    }

}
