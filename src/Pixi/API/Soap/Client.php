<?php

namespace Pixi\API\Soap;

use Pixi\API\Soap\Result;

class Client extends \SoapClient
{

    /**
     * @var \Pixi\API\Soap\Result Value will be kept
     */
    public $content;

    /**
     * The constructor is overwritten, so it can be initalized without any parameters
     *
     * @param string|null $wsdl
     * @param array|null $options
     */
    public function __construct($wsdl = null, $options = null)
    {

        if($wsdl OR $options) {
        	parent::__construct($wsdl, $options);
        }

    }

    /* (non-PHPdoc)
     * @see SoapClient::__call()
     */
    public function __call($function_name, $arguments)
    {

        if(substr($function_name, 0, 4) == 'pixi') {

            $vars = array();

            if(isset($arguments[0]) AND is_array($arguments[0]) AND count($arguments[0]) > 0) {

                foreach($arguments[0] as $key => $val) {

                    $vars[] = new \SoapVar($val, null, '', '', $key, $this->uri);

                }

            }

            $result = parent::__call($function_name, $vars);

            $this->content = new Result($result);

            return $this->content;

        } else {

            return parent::__call($function_name, $arguments);

        }

    }

    /**
     * @param \Pixi\API\Soap\Options $options Options for the soap client.
     */
    public function setPixiOptions($options)
    {

        parent::__construct(null, $options->getOptions());

    }

}
