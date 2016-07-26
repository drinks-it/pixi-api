<?php

$soapResult = new stdClass();
$soapResult->SqlRowSet = new stdClass();
$soapResult->SqlResultCode = 0;
$soapResult->SqlRowSet->diffgram = new stdClass();
$soapResult->SqlRowSet->diffgram->SqlRowSet1 = new stdClass();

$soapResult->SqlRowSet->diffgram->SqlRowSet1->row[] = new stdClass();
$soapResult->SqlRowSet->diffgram->SqlRowSet1->row[0]->ShopID = "FLO";
$soapResult->SqlRowSet->diffgram->SqlRowSet1->row[0]->ShopName = "FLO";
$soapResult->SqlRowSet->diffgram->SqlRowSet1->row[0]->Country = "D";

$soapResult->SqlRowSet->diffgram->SqlRowSet1->row[] = new stdClass();
$soapResult->SqlRowSet->diffgram->SqlRowSet1->row[1]->ShopID = "URO";
$soapResult->SqlRowSet->diffgram->SqlRowSet1->row[1]->ShopName = "URO";
$soapResult->SqlRowSet->diffgram->SqlRowSet1->row[1]->Country = "D";


return $soapResult;
