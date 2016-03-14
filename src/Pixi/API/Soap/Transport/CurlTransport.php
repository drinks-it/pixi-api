<?php

namespace Pixi\API\Soap\Transport;

class CurlTransport
{
    
    public $options;
    
    public $ch;
    
    public function __construct($options)
    {
        $this->options = $options;
    }
    
    public function createClient()
    {
        
        $this->ch = curl_init();
        
        curl_setopt_array($this->ch, [
            CURLOPT_URL             => $this->options['location'],
            CURLOPT_RETURNTRANSFER  => true,
            CURLOPT_POST            => true,
            CURLOPT_USERPWD         => $this->options['login'] . ':' . $this->options['password'],
            CURLOPT_SSL_VERIFYPEER  => false
        ]);
        
        return $this->ch;
        
    }
    
    public function __doRequest($request, $location = NULL, $action = NULL, $version = NULL)
    {
        
        $this->createClient();
        
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, $request);
        
        curl_setopt(
            $this->ch, 
            CURLOPT_HTTPHEADER, 
            [
                'xapp: ' . 'some-app',
                'soapaction: "' . $this->options['uri']  . substr($action, strpos($action, '#') + 1) . '"',
                'Content-length: ' . strlen($request),
                'Content-type: text/xml'
            ]
        );
        
        $fp = fopen('php://temp', 'r+');
        
        curl_setopt($this->ch, CURLOPT_BUFFERSIZE, 64);
        curl_setopt($this->ch, CURLOPT_FILE, $fp);
        
        return curl_exec($this->ch);

        while(!feof($fp)) {
            
            $meText = stream_get_line($fp, 100);
            
            echo "$meText\n now";
            
        }
        
        var_dump($fp);
        
        curl_close($this->ch);
        
        die();
        
        return $response;
        
    }
    
}
