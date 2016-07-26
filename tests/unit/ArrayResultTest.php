<?php

namespace Pixi\API\Soap\Tests;

use \Pixi\API\Soap\Result\ArrayResult;

class ArrayResultTest extends \PHPUnit_Framework_TestCase
{
    private $resultSet;

    protected function setUp()
    {
        $this->resultSet = array(
            'resultSet' => array(
                array(
                    'ShopID'   => 'ABC',
                    'ShopName' => 'ABC Testshop',
                    'Address'  => 'Walter-Gropius-Str. 15',
                    'City'     => 'Muenchen',
                    'ZIP'      => '80807',
                    'Country'  => 'D',
                ),
                array(
                    'ShopID'   => 'DEF',
                    'ShopName' => 'DEF new Testshop',
                    'Address'  => 'Haupstraße 15',
                    'City'     => 'München',
                    'ZIP'      => '81245',
                    'Country'  => 'D',
                ),
            ),
            'error'     => array(),
        );
    }

    protected function tearDown()
    {
        $this->resultSet = null;
    }

    public function testGetResultSet()
    {
        $arrayResult = new ArrayResult();
        $arrayResult->setResultSet($this->resultSet);

        $this->assertSame($this->resultSet['resultSet'], $arrayResult->getResultSet());
    }

    /**
     * @expectedException \Pixi\API\Soap\Result\ResultException
     */
    public function testGetResultSetWithErrors()
    {
        $result = array(
            'error'     => array(array('Message' => 'Error', 'Number' => 400)),
            'resultSet' => array(),
        );

        $arrayResult = new ArrayResult();
        $arrayResult->setResultSet($result);

        $arrayResult->getResultSet();
    }

    public function testSetIgnoreErrors()
    {
        $arrayResult = new ArrayResult();

        $this->assertInstanceOf(ArrayResult::class, $arrayResult->setIgnoreErrors(true));
    }
}
