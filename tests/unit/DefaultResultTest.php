<?php
/**
 * Created by PhpStorm.
 * User: mantolovic
 * Date: 6.7.2016
 * Time: 16:48
 */

namespace Pixi\API\Soap\Tests;

use Pixi\API\Soap\Result\DefaultResult;

class DefaultResultTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Pixi\API\Soap\Result\DefaultResult;
     */
    private $soapResult;

    private $expectedResult = array(
        array(
            'ShopID'   => 'FLO',
            'ShopName' => 'FLO',
            'Country'  => 'D',
        ),
        array(
            'ShopID'   => 'URO',
            'ShopName' => 'URO',
            'Country'  => 'D',
        ),
    );

    private $expectedResultSetForMultipleResultSets = array(
        0 =>
            array(
                0 =>
                    array(
                        0 =>
                            array(
                                'ShopID'   => 'FLO',
                                'ShopName' => 'FLO',
                                'Country'  => 'D',
                            ),
                        1 =>
                            array(
                                'ShopID'   => 'URO',
                                'ShopName' => 'URO',
                                'Country'  => 'D',
                            ),
                    ),
            ),
        1 =>
            array(
                0 =>
                    array(
                        0 =>
                            array(
                                'ShopID'   => 'FLO',
                                'ShopName' => 'URO',
                                'Country'  => 'D',
                            ),
                        1 =>
                            array(
                                'ShopID'   => 'FLO',
                                'ShopName' => 'URO',
                                'Country'  => 'D',
                            ),
                    ),
            ),
    );

    protected function setUp()
    {
        $soapResult = require __DIR__ . "/../_data/SingleSoapResultSetForMultipleRows.php";
        $this->soapResult = $soapResult;
    }

    protected function tearDown()
    {
        $this->soapResult = null;
    }

    public function testGetResultSetForSingleRow()
    {
        $soapResult = require __DIR__ . "/../_data/SingleSoapResultSetForSingleRow.php";
        $expectedResult = array(
            0 =>
                array(
                    'ShopID'   => 'FLO',
                    'ShopName' => 'FLO',
                    'Country'  => 'D',
                ),
        );

        $defaultResult = new DefaultResult();
        $defaultResult->setResultSet($soapResult);

        $this->assertSame($expectedResult, $defaultResult->getResultSet());
    }

    public function testGetResultSetWithMultipleRows()
    {
        $defaultResult = new DefaultResult();
        $defaultResult->setResultSet($this->soapResult);

        $this->assertSame($this->expectedResult, $defaultResult->getResultSet());
    }


    public function testGetResultForEmptyResult()
    {
        $soapResult = new \stdClass();
        $soapResult->SqlRowSet = new \stdClass();
        $soapResult->SqlResultCode = 0;

        $defaultResult = new DefaultResult();
        $defaultResult->setResultSet($soapResult);

        $this->assertSame(array(), $defaultResult->getResultSet());
    }

    public function testGetResultWhenResultIsFunctionCall()
    {
        $defaultResult = new DefaultResult();
        $defaultResult->setResultSet('function');

        $this->assertSame('function', $defaultResult->getResultSet());
    }

    /**
     * @expectedException Exception
     */
    public function testGetResultWhenThereIsError()
    {
        $defaultResult = new DefaultResult();
        $defaultResult->setResultSet(array());

        $defaultResult->getResultSet();
    }

    public function testGetResultCode()
    {
        $defaultResult = new DefaultResult();
        $defaultResult->setResultSet($this->soapResult);

        $this->assertSame(0, $defaultResult->getResultCode());
    }

    public function testGetResultMessage()
    {
        $soapResult = require __DIR__ . "/../_data/SoapErrorResult.php";

        $defaultResult = new DefaultResult();
        $defaultResult->setResultSet($soapResult);

        $this->assertSame(array($soapResult->SqlMessage), $defaultResult->getMessages());
    }

    public function testGetNoResultMessage()
    {

        $defaultResult = new DefaultResult();
        $defaultResult->setResultSet(array());

        $this->assertSame(array(), $defaultResult->getMessages());
    }

    public function testGetResultForMultipleResultSets()
    {
        $soapResult = require __DIR__ . "/../_data/MultipleSoapResultSets.php";


        $defaultResult = new DefaultResult();
        $defaultResult->setResultSet($soapResult);

        $this->assertSame($this->expectedResultSetForMultipleResultSets, $defaultResult->getResultSet());
    }

    public function testHasMultipleResultSets()
    {
        $soapResult = require __DIR__ . "/../_data/MultipleSoapResultSets.php";

        $defaultResult = new DefaultResult();
        $defaultResult->setResultSet($soapResult);

        $this->assertSame(true, $defaultResult->hasMultipleResultsets());
    }

    public function testDoesNotHaveMultipleResultSets()
    {
        $defaultResult = new DefaultResult();
        $defaultResult->setResultSet(array());

        $this->assertSame(false, $defaultResult->hasMultipleResultsets());
    }

    public function testGetValue()
    {
        $defaultResult = new DefaultResult();
        $defaultResult->setResultSet(function () {
        });

        $this->assertInstanceOf('Closure', $defaultResult->getValue());
    }
}
