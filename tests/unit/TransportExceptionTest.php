<?php

use Pixi\API\Soap\Transport\TransportException;

class TransportExceptionTest extends \PHPUnit_Framework_TestCase
{

    public function testConstructMethod()
    {
        $exception = new TransportException('Message', 400, curl_init());

        $this->assertInstanceOf(TransportException::class, $exception);
    }
}
