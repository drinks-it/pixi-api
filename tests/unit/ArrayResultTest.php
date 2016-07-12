<?php

namespace Pixi\API\Soap\Tests;

use \Pixi\API\Soap\Result\ArrayResult;

class ArrayResultTest extends \PHPUnit_Framework_TestCase
{
    private $resultSet;

    protected function setUp()
    {
        $this->resultSet = array(
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

        $this->assertSame($this->resultSet, $arrayResult->getResultSet());
    }

    public function testSetIgnoreErrors()
    {
        $arrayResult = new ArrayResult();

        $this->assertInstanceOf(ArrayResult::class, $arrayResult->setIgnoreErrors(true));
    }
}
