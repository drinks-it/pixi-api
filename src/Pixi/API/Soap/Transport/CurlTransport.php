<?php

namespace Pixi\API\Soap\Transport;
use Pixi\AppsFactory\Environment;
use Pixi\Xml\Parser\Sax;
use Pixi\API\Soap\Exception;

class CurlTransport implements TransportInterface
{
    
    public $options;
    
    public $ch;
    
    public function setOptions($options)
    {
        $this->options = $options;
        return $this;
    }
    
    public function createClient()
    {
        
        $this->ch = curl_init();
        
        curl_setopt_array($this->ch, [
            CURLOPT_URL             => $this->options['location'],
            CURLOPT_RETURNTRANSFER  => true,
            CURLOPT_POST            => true,
            CURLOPT_USERPWD         => $this->options['login'] . ':' . $this->options['password'],
            CURLOPT_SSL_VERIFYPEER  => false,
            CURLOPT_BUFFERSIZE      => 1024
        ]);
        
        return $this->ch;
        
    }
    
    public function __doRequest($request, $location = NULL, $action = NULL, $version = NULL)
    {
        
        $parser = new Sax();
        $listener = new CurlParserListener();
        $parser->dispatcher->addSubscriber($listener);
        
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
            throw new PixiApiException("Curl Error", curl_error($this->ch));
        }

        //We do not close the curl handle because of the performance increase when making multiple api requests.

        //we call xml_parser_free
        $parser->__destruct();

        return $listener->getResultset();
        
    }

}
