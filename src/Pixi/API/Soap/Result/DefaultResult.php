<?php

namespace Pixi\API\Soap\Result;

/**
 * This object contains a result from pixi* API
 *
 * @author Florian Seidl
 */
class DefaultResult implements ResultInterface
{

    private $_result = null;

    /**
     * Whether result faults should be ignored or not
     * @var bool
     */
    private $ignore_errors = false;


    public function setResultSet($result)
    {
        $this->_result = $result;
    }

    /**
     * GetResultSet
     *
     * Returns a result set from the result
     *
     * @param int $i Index of the resultset, starting with 1 (default = 1)
     * @return array Content of the requested resultset
     */
    function getResultSet()
    {

        $result = $this->_result;

        /* Return immediatelly if result is an function call result */
        if (!is_array($result) && !is_object($result)) {
            return $result;
        } else {
            $result = $this->object2array($result);
        }

        /* Check if results are present */
        if ($this->findKey($result, 'diffgram')) {

            if (isset($result['SqlRowSet'])) {
                return $this->formatResult($result['SqlRowSet']);
            } else {
                // we get an array when there are variables in soap api output
                foreach ($result as $subResult) {
                    if (isset($subResult['SqlRowSet'])) {
                        return $this->formatResult($subResult['SqlRowSet']);
                    }
                }
            }
        }

        /* There is no result set, but there is also no error occured */
        if (isset($result['SqlResultCode']) && $result['SqlResultCode'] == 0) {
            return array();
        }

        if (!$this->ignore_errors AND is_array($result) AND isset($result['SqlMessage'])) {

            throw new ResultException(
                $result['SqlMessage']['Message'],
                $result['SqlMessage']['Number']
            );

        }

    }

    /**
     * FormatResult
     *
     * Formats a soap result array to a normalized array.
     *
     * @param array $data
     * @return array
     */
    private function formatResult($data)
    {
        /* Check if result has multiple row sets */
        if (!empty($data['diffgram'])) {

            /* Check if the row of the row set has multiple items */
            if (!isset($data['diffgram']['SqlRowSet1']['row'][0])) {
                /* Return single row in array for unified results */
                return array($data['diffgram']['SqlRowSet1']['row']);
            }

            return $data['diffgram']['SqlRowSet1']['row'];

        } elseif (!empty($data[0]['diffgram'])) {

            $result = array();

            for ($i = 0; $i < count($data); $i++) {

                $j = $i + 1;
                $rowSet = "SqlRowSet" . $j;

                if (isset($data[$i]['diffgram'][$rowSet])) {
                    $result[] = array($data[$i]['diffgram'][$rowSet]['row']);
                }

            }

            return $result;

        }

        return array();
    }

    /**
     * Object2Array
     *
     * Transfers an object to array
     *
     * @param mixed $obj
     * @return array
     */
    private function object2array($obj)
    {
        $_arr = is_object($obj) ? get_object_vars($obj) : $obj;
        $arr = array();
        foreach ($_arr as $key => $val) {
            $val = (is_array($val) || is_object($val)) ? $this->object2array($val) : $val;
            $arr[$key] = $val;
        }
        return $arr;
    }

    /**
     * FindKey
     *
     * Searches for a specific key in an multi
     * array and returns either true or false.
     *
     * @param array $array
     * @param string $needle
     * @return bool
     */
    private function findKey($haystack, $needle)
    {
        $result = array_key_exists($needle, $haystack);
        if ($result) {
            return $result;
        }
        foreach ($haystack as $v) {
            if (is_array($v)) {
                $result = $this->findKey($v, $needle);
            }
            if ($result) {
                return $result;
            }
        }
        return $result;
    }

    /**
     * returns the result code of this result
     *
     * @return string Code from SQL Server
     */
    function getResultCode()
    {
        return $this->isResultSet() ? $this->_result->SqlResultCode : null;
    }

    /**
     * returns the whole result from pixi* API
     *
     * @return object
     */
    function getResult()
    {
        return $this->_result;
    }

    /**
     * returns the value of this result if it was a function
     *
     * @return mixed
     */
    function getValue()
    {
        return is_callable($this->_result) ? $this->_result : null;
    }

    /**
     * whether this result has multiple resultsets or not
     *
     * @return bool
     */
    function hasMultipleResultSets()
    {
        if ($this->isResultSet() && !empty($this->_result->SqlRowSet[0])) {
            return true;
        }

        return false;
    }

    /**
     * whether this result is a resultset
     *
     * @return bool
     */
    function isResultSet()
    {
        return is_object($this->_result);
    }


    /**
     * Sets whether to ignore result faults or not.
     * @param string $b true if SOAP faults should be ignored, false if not
     */
    public function setIgnoreErrors($b = true)
    {
        $this->ignore_errors = $b;
    }

    /**
     * true, if this result has one or more SQL messages, false if not
     * @return bool
     */
    public function hasMessages()
    {
        return isset($this->_result->SqlMessage);
    }

    /**
     * returns all SQL messages available in this result
     * @return array
     */
    public function getMessages()
    {
        if ($this->hasMessages()) {
            return is_array($this->_result->SqlMessage) ? $this->_result->SqlMessage : array($this->_result->SqlMessage);
        } else {
            return array();
        }
    }


}
