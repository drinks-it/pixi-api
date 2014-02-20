<?php

namespace Pixi\API\Soap\Tests;

use Pixi\API\Soap\Result;

class ResultTest extends \PHPUnit_Framework_TestCase
{
    protected $result;

    protected $row;
    protected $rowReflectionProperty;

    protected $sqlRowSet1;
    protected $sqlRowSet1ReflectionProperty;

    protected $diffgram;
    protected $diffgramReflectionProperty;

    protected $soapResult;
    protected $soapResultReflectionProperty;

    protected function setUp()
    {
        $this->buildResultSet(null);
    }

    protected function tearDown()
    {
        $this->result = null;
    }

    protected function buildResultSet($content)
    {
        $this->soapResult = new SoapResult;
        $soapResultReflection = new \ReflectionClass($this->soapResult);
        $this->soapResultReflectionProperty = $soapResultReflection->getProperty('SqlRowSet');
        $this->soapResultReflectionProperty->setAccessible(true);

        $this->diffgram = new diffgram;
        $diffgramReflection = new \ReflectionClass($this->diffgram);
        $this->diffgramReflectionProperty = $diffgramReflection->getProperty('diffgram');
        $this->diffgramReflectionProperty->setAccessible(true);

        $this->sqlRowSet1 = new sqlRowSet1;
        $sqlRowSet1Reflection = new \ReflectionClass($this->sqlRowSet1);
        $this->sqlRowSet1ReflectionProperty = $sqlRowSet1Reflection->getProperty('SqlRowSet1');
        $this->sqlRowSet1ReflectionProperty->setAccessible(true);

        $this->row = new row;
        $rowReflection = new \ReflectionClass($this->row);
        $this->rowReflectionProperty = $rowReflection->getProperty('row');
        $this->rowReflectionProperty->setAccessible(true);

        $this->rowReflectionProperty->setValue($this->row, $content);
        $this->sqlRowSet1ReflectionProperty->setValue($this->sqlRowSet1, $this->row);
        $this->diffgramReflectionProperty->setValue($this->diffgram, $this->sqlRowSet1);
        $this->soapResultReflectionProperty->setValue($this->soapResult, $this->diffgram);

        $this->result = new Result($this->soapResult);
    }

    public function testGetResultsetMultipleRows()
    {
        $testData = require __DIR__."/../_data/ResultSet.php";

        $expectedResultMultiple = array(
            array(
                'ShopID' => 'ABC',
                'ShopName' => 'ABC Testshop',
                'Address' => 'Walter-Gropius-Str. 15',
                'City' => 'Muenchen',
                'ZIP' => '80807',
                'Country' => 'D'
            ),
            array(
                'ShopID' => 'DEF',
                'ShopName' => 'DEF new Testshop',
                'Address' => 'Haupstraße 15',
                'City' => 'München',
                'ZIP' => '81245',
                'Country' => 'D'
            )
        );

        $this->buildResultSet($testData['RowSetMultipleRows']);
        $this->assertSame($expectedResultMultiple, $this->result->getResultset());
    }

    public function testGetResultsetSingleRow()
    {
        $testData = require __DIR__."/../_data/ResultSet.php";

        $expectedResultSingle = array(
            array(
                'ShopID' => 'NEW',
                'ShopName' => 'NEW Testshop',
                'Address' => 'Große-Fleischergasse',
                'City' => 'Leipzig',
                'ZIP' => '1337',
                'Country' => 'D'
            )
        );

        $this->buildResultSet($testData['RowSetSingleRow']);
        $this->assertSame($expectedResultSingle, $this->result->getResultset());
    }

    public function testGetResultFunctionCall()
    {
        $newResult = new Result('string');
        $this->assertSame('string', $newResult->getResultset());
    }

    public function testGetResultMultipleRowSets()
    {
        $hardcodedResultSetArray = array(
            'SqlRowSet' => array(
                array(
                    'diffgram' => array(
                        'SqlRowSet1' => array(
                            'row' => array(
                                'Key' => 'value'
                            )
                        )
                    )
                ),
                array(
                    'diffgram' => array(
                        'SqlRowSet2' => array(
                            'row' => array(
                                array(
                                    'Key' => 'value',
                                    'Key2' => 'value2'
                                ),
                                array(
                                    'Key' => 'value'
                                )
                            )
                        )
                    )
                )
            )
        );

        $expectedResultOfMultipleRowsets = array(
            array(
                array(
                    'Key' => 'value'
                )
            ),
            array(
                array(
                    array(
                        'Key' => 'value',
                        'Key2' => 'value2'
                    ),
                    array(
                        'Key' => 'value'
                    )
                )
            )
        );

        $anotherResult = new Result($hardcodedResultSetArray);

        $this->assertSame($expectedResultOfMultipleRowsets, $anotherResult->getResultset());
    }

    /**
     * @expectedException Exception
     */
    public function testGetResultsetExceptions()
    {
        $result = new Result(array('throws' => 'exception'));
        $result->getResultset();
    }

    /**
     * @expectedException Exception
     */
    public function testResultMessage()
    {
        $messageException = array(
            'SqlMessage' =>
                array('Message' => 'Invalid Parameter')
        );

        $result = new Result($messageException);
        $result->getResultset();
    }

    public function testGetResultCode()
    {
        $result = new Result(null);
        $this->assertNull($result->getResultCode());

        $result = new Result(array('SqlResultCode' => 4));
        $this->assertSame(4, $result->getResultCode());
    }

    public function testGetResult()
    {
        $inputOutputArray = array(
            'Input' => array(
                'Output'
            )
        );

        $result = new Result($inputOutputArray);

        $this->assertSame($inputOutputArray, $result->getResult());
    }

    public function testGetValue()
    {
        $resultArray = array('Value' => array('Key' => 'Value'));
        $result = new Result($resultArray);
        $this->assertNull($result->getValue());

        $result = new Result('string');
        $this->assertSame('string', $result->getValue());
    }

    public function testHasMutlipleResultSets()
    {
        $result = new Result('string');
        $this->assertFalse($result->hasMultipleResultsets());

        $hardcodedResultSetArray = array(
            'SqlRowSet' => array(
                array(
                    'diffgram' => array(
                        'SqlRowSet1' => array(
                            'row' => array(
                                'Key' => 'value'
                            )
                        )
                    )
                ),
                array(
                    'diffgram' => array(
                        'SqlRowSet2' => array(
                            'row' => array(
                                array(
                                    'Key' => 'value',
                                    'Key2' => 'value2'
                                ),
                                array(
                                    'Key' => 'value'
                                )
                            )
                        )
                    )
                )
            )
        );

        $result = new Result($hardcodedResultSetArray);
        $this->assertTrue($result->hasMultipleResultsets());
    }
}

/**
 * Dummy classes for testing
 */
class SoapResult
{
    public $SqlRowSet;
}

class diffgram
{
    public $diffgram;
}

class sqlRowSet1
{
    public $SqlRowSet1;
}

class row
{
    public $row;
}
