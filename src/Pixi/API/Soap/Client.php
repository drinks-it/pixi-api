<?php
namespace Pixi\API\Soap;

use Pixi\API\Soap\Result;
use Pixi\API\Soap\Exception\PixiApiException;
use Pixi\AppsFactory\Environment;
use Pixi\API\Soap\Transport\CurlTransport;

class Client extends \SoapClient
{

    /**
     *
     * @var \Pixi\API\Soap\Result Value will be kept
     */
    public $content;
    
    /**
     * Whether result faults should be ignored or not
     *
     * @var bool
     */
    private $ignore_errors = false;
    
    /**
     * Http stream context for the soap client
     * 
     * @var string
     */
    public $headerStream;
    
    /**
     * Options for the underlying stream_context
     * 
     * @var array
     */
    public $streamContext = array();
    
    /**
     * @var string|object Object name for the Result object
     */
    public $resultObject = '\Pixi\API\Soap\Result\DefaultResult';
    
    /**
     * @var string|object Name of the transport object or object
     */
    public $transportObject = false;
    
    /**
     * @var array Options which are injected in the constructor.
     */
    public $clientOptions = array();
    
    /**
     * @var bool If true, client will use curl for transport
     */
    public $useCurl = true;
    
    /**
     * The constructor is overwritten, so it can be initalized without any parameters
     *
     * @param string|null $wsdl            
     * @param array|null $options            
     */
    public function __construct($wsdl = null, $options = null)
    {

        if ($wsdl or $options) {
            
            $this->clientOptions = $options;
            
            if(isset($options['stream_context']) AND is_array($options['stream_context'])) {
                $this->streamContext = $options['stream_context'];
            }
            
            $this->headerStream = stream_context_create();
            $options['stream_context'] = $this->headerStream;
            parent::__construct($wsdl, $options);
            
        }
        
    }

    /*
     * (non-PHPdoc)
     * @see SoapClient::__call()
     */
    public function __call($function_name, $arguments)
    {
        
        if (substr($function_name, 0, 4) == 'pixi') {
            
            $vars = array();
            
            if (isset($arguments[0]) and is_array($arguments[0]) and count($arguments[0]) > 0) {
                
                foreach ($arguments[0] as $key => $val) {
                    
                    $vars[] = new \SoapVar($val, null, '', '', $key, $this->uri);
                }
            }
            
            $context = array_merge(
                $this->streamContext, 
                ['http' => [
                    'header' => 'xapp: ' . Environment::getAppId() . "\r\n" . 
                                'soapaction: "' . $this->uri  . $function_name . '"' . "\r\n"   
                ]]
            );
            
            stream_context_set_option($this->headerStream, $context);
            
            $this->content = $this->getResultObject();
            
            $result = NULL;
            
            try {
                $result = parent::__call($function_name, $vars);
            } catch(\Exception $e) {
                
            }
            
            $this->content->setResultSet($result);
            
            $this->content->setIgnoreErrors($this->ignore_errors);
            
            return $this->content;
            
        } else {
            
            return parent::__call($function_name, $arguments);
            
        }
        
    }
    
    public function __doRequest($request, $location, $action, $version, $oneWay = 0)
    {
        
        if($this->transportObject) {
            
            $transport = $this->getTransportObject();
            $transport->setOptions($this->clientOptions);
            $this->content->setResultSet($transport->__doRequest($request, $location, $action, $version));
            
            return;
                        
        } else {
            
            return parent::__doRequest($request, $location, $action, $version);
            
        }
        
    }

    /**
     *
     * @param \Pixi\API\Soap\Options $options
     *            Options for the soap client.
     */
    public function setPixiOptions($options)
    {
        $this->__construct(null, $options->getOptions());
    }

    /**
     * Sets whether to ignore result faults or not.
     * 
     * @param string $b
     *            if SOAP faults should be ignored, false if not
     */
    public function setIgnoreErrors($b = true)
    {
        $this->ignore_errors = $b;
    }

    /**
     * GetRevision
     *
     * This method returns the current API revision that is applied to
     * the pixi database.
     *
     * @access public
     * @return mixed Revisionnumber or Exception
     */
    public function getRevision()
    {
        
        $request = $this->pixiSysGetCurrentRevision();
        $response = $request->getResultSet();
        
        if (count($response) > 0 && isset($response[0]['CurrentRevision'])) {
            return $response[0]['CurrentRevision'];
        }
        
        $request = $this->pixiSysGetCallInfo(array(
            'CallName' => 'pixiSysGetCurrentRevision'
        ));
        $response = $request->getResultSet();
        
        if (isset($response[0]['StatusMessage']) && strstr($response[0]['StatusMessage'], 'not installed')) {
            throw new PixiApiException("Could not find an API revision number. API call pixiSysGetCurrentRevision is missing on the database", 1);
        }
        
        throw new PixiApiException("Couldn't find and API revision number", 0);
        
    }
    
    /**
     * Clear the the content variable of the object. 
     * Cleaning up memory after big queries.
     */
    public function clearContent()
    {
        
        $this->content = null;
        
    }

    public function getResultObject()
    {
        
        if(is_string($this->resultObject)) {
            $this->resultObject = new $this->resultObject;
        }
        
        return $this->resultObject;
        
    }

    public function setResultObject($resultObject)
    {
        $this->resultObject = $resultObject;
        return $this;
    }
    
    public function setTransportObject($object)
    {
        $this->transportObject = $object;
        return $this;
    }
    
    public function getTransportObject()
    {
        
        if(is_string($this->transportObject)) {
            $this->transportObject = new $this->transportObject;
        }
         
        return $this->transportObject;
        
    }
 
}
