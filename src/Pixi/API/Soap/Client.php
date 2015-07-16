<?php
namespace Pixi\API\Soap;

use Pixi\API\Soap\Result;
use Pixi\API\Soap\Exception\PixiApiException;
use Pixi\AppsFactory\Environment;

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
     * The constructor is overwritten, so it can be initalized without any parameters
     *
     * @param string|null $wsdl            
     * @param array|null $options            
     */
    public function __construct($wsdl = null, $options = null)
    {
        
        if ($wsdl or $options) {
            
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
                ['http' => ['header' => 'xapp: ' . Environment::getAppId() . "\r\n" . 'soapaction: "' . $this->uri  . $function_name . '"' . "\r\n"]]
            );
            
            stream_context_set_option($this->headerStream, $context);
            
            $result = parent::__call($function_name, $vars);
            
            $this->content = new Result($result);
            $this->content->setIgnoreErrors($this->ignore_errors);
            return $this->content;
        } else {
            
            return parent::__call($function_name, $arguments);
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
}
