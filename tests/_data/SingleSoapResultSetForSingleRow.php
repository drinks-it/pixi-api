<?php

$soapResult = new stdClass();
$soapResult->SqlRowSet = new stdClass();
$soapResult->SqlResultCode = 0;
$soapResult->SqlRowSet->diffgram = new stdClass();
$soapResult->SqlRowSet->diffgram->SqlRowSet1 = new stdClass();

$soapResult->SqlRowSet->diffgram->SqlRowSet1->row = new stdClass();
$soapResult->SqlRowSet->diffgram->SqlRowSet1->row->ShopID = "FLO";
$soapResult->SqlRowSet->diffgram->SqlRowSet1->row->ShopName = "FLO";
$soapResult->SqlRowSet->diffgram->SqlRowSet1->row->Country = "D";

return $soapResult;
