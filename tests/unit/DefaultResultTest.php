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
            'ShopID'      => 'STG',
            'ShopName'    => 'Hier Webadresse des Shops eintragen!',
            'Address'     => 'Hier Strasse und Hausnummer eintragen!',
            'City'        => 'Hier Ort eintragen!',
            'State'       => 'GER',
            'ZIP'         => 'PLZ',
            'Country'     => 'D',
            'Contact'     => 'Hier Ansprechpartner eintragen!',
            'Phone'       => 'Hier Telefonnummer von Ansprechpartner eintragen!',
            'Fax'         => 'Hier Faxnummer eintragen',
            'ShopCompany' => 'Firma',
            'eMail'       => 'installation@pixi.eu',
            'BIC'         => 'UstID und Steuernummer',
            'IBAN'        => 'IBAN und SWIFT',
            'KontoBLZ'    => 'Kontonummer und BLZ',
            'BankName'    => 'Bankname',
        ),
        array(
            'ShopID'   => 'URO',
            'ShopName' => 'URO',
            'Country'  => 'D',
        ),
    );

    protected function setUp()
    {
        $soapResult = new SoapResult();
        $soapResult->SqlRowSet = new SqlRowSet();
        $soapResult->SqlRowSet->diffgram = new diffgram();
        $soapResult->SqlRowSet->diffgram->SqlRowSet1 = new SqlRowSet1();
        $soapResult->SqlRowSet->diffgram->SqlRowSet1->row = new row();

        $this->soapResult = $soapResult;
    }

    protected function tearDown()
    {
        $this->soapResult = null;
    }

    public function testGetResultSetSingleRow()
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

        $this->soapResult->SqlRowSet->diffgram->SqlRowSet1->row = $testData['RowSetSingleRow'];

        $defaultResult = new DefaultResult();
        $defaultResult->setResultSet($this->soapResult);

        $this->assertSame($expectedResultSingle, $defaultResult->getResultSet());
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

        $this->soapResult->SqlRowSet->diffgram->SqlRowSet1->row = $testData['RowSetMultipleRows'];

        $defaultResult = new DefaultResult();
        $defaultResult->setResultSet($this->soapResult);

        $this->assertSame($expectedResultMultiple, $defaultResult->getResultSet());
    }
}

class SoapResult
{
    public $SqlRowSet;
    public $SqlResultCode = 0;
}

class SqlRowSet
{
    public $diffgram;
}

class diffgram
{
    public $SqlRowSet1;
}

class SqlRowSet1
{
    public $row;
}

class row
{
    public $array;
}
