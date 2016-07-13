<?php
namespace Pixi\API\Soap\Result;

class ArrayResult implements ResultInterface
{

    public $resultSet;
    
    public $error = false;
    
    /**
     *
     * {@inheritDoc}
     *
     * @see \Pixi\API\Soap\Result\ResultInterface::getResultSet()
     */
    public function getResultSet()
    {
        
        if (!$this->ignore_errors AND $this->error AND count($this->error) > 0) {
        
            throw new ResultException(
                $this->error[0]['Message'],
                $this->error[0]['Number']
            );
        
        }
        
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
            $this->error = $result['error'];
            $this->resultSet = $result['resultSet'];
        }
    }

}
