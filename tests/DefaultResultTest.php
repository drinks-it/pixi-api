<?php

use PHPUnit\Framework\TestCase;
use Pixi\API\Soap\Result\DefaultResult;

class DefaultResultTest extends TestCase
{
    /**
     * @var DefaultResult
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
        array(
            array(
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
            ),
        ),
        array(
            array(
                array(
                    'ShopID'   => 'FLO',
                    'ShopName' => 'URO',
                    'Country'  => 'D',
                ),
                array(
                    'ShopID'   => 'FLO',
                    'ShopName' => 'URO',
                    'Country'  => 'D',
                ),
            ),
        ),
    );

    protected function setUp(): void
    {
        $soapResult = require __DIR__ . "/_data/SingleSoapResultSetForMultipleRows.php";
        $this->soapResult = $soapResult;
    }

    protected function tearDown(): void
    {
        $this->soapResult = null;
    }

    public function testGetResultForSingleResultSetWithOneRow()
    {
        $soapResult = require __DIR__ . "/_data/SingleSoapResultSetForSingleRow.php";
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

    public function testGetResultForSingleResultSetWithMultipleRows()
    {
        $defaultResult = new DefaultResult();
        $defaultResult->setResultSet($this->soapResult);

        $this->assertSame($this->expectedResult, $defaultResult->getResultSet());
    }


    public function testGetResultForSingleResultSetWithEmptyResult()
    {
        $soapResult = new \stdClass();
        $soapResult->SqlRowSet = new \stdClass();
        $soapResult->SqlRowSet->diffgram = '';
        $soapResult->SqlResultCode = 0;

        $defaultResult = new DefaultResult();
        $defaultResult->setResultSet($soapResult);

        $this->assertSame(array(), $defaultResult->getResultSet());
    }

    public function testGetResultWithOutputVariables()
    {
        $soapResult = array();
        $soapResult['pixiCheckPixiUserLoginResult'] = new \stdClass();
        $soapResult['pixiCheckPixiUserLoginResult']->SqlRowSet = new \stdClass();
        $soapResult['pixiCheckPixiUserLoginResult']->SqlResultCode = 0;
        $soapResult['pixiCheckPixiUserLoginResult']->SqlRowSet->diffgram = new \stdClass();
        $soapResult['pixiCheckPixiUserLoginResult']->SqlRowSet->diffgram->SqlRowSet1 = new \stdClass();

        $soapResult['pixiCheckPixiUserLoginResult']->SqlRowSet->diffgram->SqlRowSet1->row = new \stdClass();
        $soapResult['pixiCheckPixiUserLoginResult']->SqlRowSet->diffgram->SqlRowSet1->row->ShopID = "FLO";
        $soapResult['pixiCheckPixiUserLoginResult']->SqlRowSet->diffgram->SqlRowSet1->row->ShopName = "FLO";
        $soapResult['pixiCheckPixiUserLoginResult']->SqlRowSet->diffgram->SqlRowSet1->row->Country = "D";

        $soapResult['ValidUser'] = '1';
        $soapResult['UserEmail'] = '';
        $soapResult['UserRealName'] = 'Master';

        $expectedResult = array(
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

    public function testGetResultForNoResultSet()
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

    public function testGetResultWhenThereIsError()
    {
        $this->expectException(\Pixi\API\Soap\Result\ResultException::class);

        $defaultResult = new DefaultResult();
        $defaultResult->setResultSet(array('SqlMessage' => array('Message' => 'Error', 'Number' => 400)));

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
        $soapResult = require __DIR__ . "/_data/SoapErrorResult.php";

        $defaultResult = new DefaultResult();
        $defaultResult->setResultSet($soapResult);

        $this->assertSame(array($soapResult->SqlMessage), $defaultResult->getMessages());
    }

    public function testGetResultMessageWhereThereIsNoMessage()
    {

        $defaultResult = new DefaultResult();
        $defaultResult->setResultSet(array());

        $this->assertSame(array(), $defaultResult->getMessages());
    }

    public function testGetResultForMultipleResultSets()
    {
        $soapResult = require __DIR__ . "/_data/MultipleSoapResultSets.php";


        $defaultResult = new DefaultResult();
        $defaultResult->setResultSet($soapResult);

        $this->assertSame($this->expectedResultSetForMultipleResultSets, $defaultResult->getResultSet());
    }

    public function testHasMultipleResultSets()
    {
        $soapResult = require __DIR__ . "/_data/MultipleSoapResultSets.php";

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

    public function testGetResult()
    {
        $defaultResult = new DefaultResult();
        $defaultResult->setResultSet($this->soapResult);

        $this->assertSame($this->soapResult, $defaultResult->getResult());
    }
}