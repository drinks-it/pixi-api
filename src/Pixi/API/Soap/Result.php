<?php

namespace Pixi\API\Soap;

/**
 * This object contains a result from pixi* API
 *
 * @author kober
 * @author Florian Seidl
 */
class Result
{
    private $_result = null;

    function __construct($result)
    {
        $this->_result = $result;
    }

    /**
     * GetResultset
     *
     * Returns a result set from the result
     *
     * @param int $i Index of the resultset, starting with 1 (default = 1)
     * @return array Content of the requested resultset
     */
    function getResultset()
    {
        $result = $this->_result;
        $return = array();

        /* Return immediatelly if result is an function call result */
        if(!is_array($result) && !is_object($result)) {
            return $result;
        } else {
            $result = $this->object2array($result);
        }

        /* Check if something failed and return the message */
        if($this->findKey($result, 'Message')) {
            throw new \Exception($result['SqlMessage']['Message']);
        }

        /* Check if results are present */
        if($this->findKey($result, 'diffgram')) {
            return $this->formatResult($result['SqlRowSet']);
        }

        throw new \Exception('There was an error in the incomming resultset');
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
        if(!empty($data['diffgram'])) {

            /* Check if the row of the row set has multiple items */
            if(count($data['diffgram']['SqlRowSet1']['row']) === count($data['diffgram']['SqlRowSet1']['row'], COUNT_RECURSIVE)) {
                /* Return single row in array for unified results */
                return array($data['diffgram']['SqlRowSet1']['row']);
            }
            return $data['diffgram']['SqlRowSet1']['row'];

        } else if(!empty($data[0]['diffgram'])) {

            for ($i = 0; $i < count($data); $i++) {
                $j = $i + 1;
                $rowSet = "SqlRowSet" . $j;
                $result[] = array($data[$i]['diffgram'][$rowSet]['row']);
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
        if ($result) return $result;
        foreach ($haystack as $v) {
            if (is_array($v)) {
                $result = $this->findKey($v, $needle);
            }
            if ($result) return $result;
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
        return $this->isResultset() ? $this->_result['SqlResultCode'] : null;
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
        return $this->isResultset() ? null : $this->_result;
    }

    /**
     * whether this result has multiple resultsets or not
     *
     * @return bool
     */
    function hasMultipleResultsets()
    {
        if($this->isResultset() && !empty($this->_result['SqlRowSet'][0])) {
            return true;
        }

        return false;
    }

    /**
     * whether this result is a resultset
     *
     * @return bool
     */
    function isResultset()
    {
        return is_array($this->_result);
    }

}
