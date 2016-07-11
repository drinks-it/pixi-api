<?php

$soapResult = new stdClass();
$soapResult->SqlResultCode = 0;

$soapResult->SqlRowSet[] = new stdClass();
$soapResult->SqlRowSet[0]->diffgram = new stdClass();
$soapResult->SqlRowSet[0]->diffgram->SqlRowSet1 = new stdClass();

$soapResult->SqlRowSet[0]->diffgram->SqlRowSet1->row[] = new stdClass();
$soapResult->SqlRowSet[0]->diffgram->SqlRowSet1->row[0]->ShopID = "FLO";
$soapResult->SqlRowSet[0]->diffgram->SqlRowSet1->row[0]->ShopName = "FLO";
$soapResult->SqlRowSet[0]->diffgram->SqlRowSet1->row[0]->Country = "D";

$soapResult->SqlRowSet[0]->diffgram->SqlRowSet1->row[] = new stdClass();
$soapResult->SqlRowSet[0]->diffgram->SqlRowSet1->row[1]->ShopID = "URO";
$soapResult->SqlRowSet[0]->diffgram->SqlRowSet1->row[1]->ShopName = "URO";
$soapResult->SqlRowSet[0]->diffgram->SqlRowSet1->row[1]->Country = "D";


$soapResult->SqlRowSet[] = new stdClass();
$soapResult->SqlRowSet[1]->diffgram = new stdClass();
$soapResult->SqlRowSet[1]->diffgram->SqlRowSet2 = new stdClass();

$soapResult->SqlRowSet[1]->diffgram->SqlRowSet2->row[] = new stdClass();
$soapResult->SqlRowSet[1]->diffgram->SqlRowSet2->row[0]->ShopID = "FLO";
$soapResult->SqlRowSet[1]->diffgram->SqlRowSet2->row[0]->ShopName = "URO";
$soapResult->SqlRowSet[1]->diffgram->SqlRowSet2->row[0]->Country = "D";

$soapResult->SqlRowSet[1]->diffgram->SqlRowSet2->row[] = new stdClass();
$soapResult->SqlRowSet[1]->diffgram->SqlRowSet2->row[1]->ShopID = "FLO";
$soapResult->SqlRowSet[1]->diffgram->SqlRowSet2->row[1]->ShopName = "URO";
$soapResult->SqlRowSet[1]->diffgram->SqlRowSet2->row[1]->Country = "D";

return $soapResult;
