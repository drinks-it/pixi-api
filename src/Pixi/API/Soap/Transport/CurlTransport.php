<?php

namespace Pixi\API\Soap\Transport;

use Pixi\API\Soap\ParserListener\CurlParserFaultListener;
use Pixi\AppsFactory\Environment;
use Pixi\Xml\Parser\Sax;
use Pixi\API\Soap\ParserListener\CurlParserListener;
use Pixi\API\Soap\ParserListener\CurlParserErrorListener;

/**
 * Class CurlTransport
 * @package Pixi\API\Soap\Transport
 */
class CurlTransport implements TransportInterface
{
    /**
     * @var array
     */
    public $options;

    /**
     * @var resource|false a cURL handle on success, false on errors.
     */
    public $ch;

    /**
     * @param array $options
     * @return $this
     */
    public function setOptions($options)
    {
        $this->options = $options;
        return $this;
    }

    /**
     * @return false|resource
     */
    public function createClient()
    {
        $this->ch = curl_init();
        
        curl_setopt_array($this->ch, [
            CURLOPT_URL             => $this->options['location'],
            CURLOPT_RETURNTRANSFER  => true,
            CURLOPT_POST            => true,
            CURLOPT_USERPWD         => $this->options['login'] . ':' . $this->options['password'],
            CURLOPT_SSL_VERIFYPEER  => false,
            CURLOPT_SSL_VERIFYHOST  => false,
            CURLOPT_BUFFERSIZE      => 1024
        ]);
        
        return $this->ch;
    }

    /**
     * @param $request
     * @param null $location
     * @param null $action
     * @param null $version
     * @return array
     * @throws CurlSoapFault
     * @throws TransportException
     */
    public function __doRequest($request, $location = NULL, $action = NULL, $version = NULL)
    {
        $parser = new Sax();
        $listener = new CurlParserListener();
        $errorListener = new CurlParserErrorListener();
        $faultListener = new CurlParserFaultListener();

        $parser->dispatcher->addSubscriber($listener);
        $parser->dispatcher->addSubscriber($errorListener);
        $parser->dispatcher->addSubscriber($faultListener);

        $this->createClient();
        
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, $request);
        
        curl_setopt(
            $this->ch, 
            CURLOPT_HTTPHEADER, 
            [
                'xapp: ' . Environment::getAppId(),
                'soapaction: "' . $this->options['uri'] . substr($action, strpos($action, '#') + 1) . '"',
                'Content-length: ' . strlen($request),
                'Content-type: text/xml'
            ]
        );

        curl_setopt($this->ch, CURLOPT_WRITEFUNCTION, function($a, $b) use ($parser) {
            $parser->parse($b);
            return strlen($b);
        });
        
        curl_exec($this->ch);
        
        if (curl_error($this->ch)) {
            throw new TransportException(curl_error($this->ch), curl_errno($this->ch), $this->ch);
        }

        //We do not close the curl handle because of the performance increase when making multiple api requests.

        if ($faultListener->getResultSet()) {
            throw new CurlSoapFault($faultListener->getResultSet()[0]['Value'], $faultListener->getResultSet()[0]['Text']);
        }

        //we call xml_parser_free
        $parser->__destruct();

        return ['resultSet' => $listener->getResultset(), 'error' => $errorListener->getResultSet()];
    }

}
