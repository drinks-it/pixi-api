<?php

namespace Pixi\API\Soap\Result;

/**
 * Class ArrayResult
 * @package Pixi\API\Soap\Result
 */
class ArrayResult implements ResultInterface
{
    /**
     * @var array
     */
    public $resultSet;

    /**
     * @var bool
     */
    public $error = false;

    /**
     * @var bool
     */
    private $ignore_errors = false;

    /**
     * @return array
     * @throws ResultException
     */
    public function getResultSet()
    {

        if (!$this->ignore_errors
            AND $this->error
            AND count($this->error) > 0
            AND !$this->resultSet) {

            throw new ResultException(
                $this->error[0]['Message'],
                $this->error[0]['Number']
            );

        }

        return $this->resultSet;
    }

    /**
     * @param $bool
     * @return $this|mixed
     */
    public function setIgnoreErrors($bool)
    {
        $this->ignore_errors = $bool;
        return $this;
    }


    /**
     * @param $result
     * @return mixed|void
     */
    public function setResultSet($result)
    {
        if (is_array($result)) {
            $this->error = $result['error'];
            $this->resultSet = $result['resultSet'];
        }
    }

}
