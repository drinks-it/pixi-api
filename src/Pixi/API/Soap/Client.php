<?php

namespace Pixi\API\Soap;

use Pixi\AppsFactory\Environment;

/**
 * Class Client
 * @package Pixi\API\Soap
 */
class Client extends \SoapClient
{
    /**
     *
     * @var \Pixi\API\Soap\Result\ResultInterface Value will be kept
     */
    public $content;

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
     * @var \Exception Last exception thrown by soap client
     */
    public $lastException = false;

    /**
     * The constructor is overwritten, so it can be initalized without any parameters
     *
     * @param string|null $wsdl
     * @param array|null $options
     */
    public function __construct(?string $wsdl = null, ?array $options = null)
    {

        if ($wsdl or $options) {

            $this->clientOptions = $options;

            if (isset($options['stream_context']) AND is_array($options['stream_context'])) {
                $this->streamContext = $options['stream_context'];
            }

            $this->headerStream = stream_context_create();
            $options['stream_context'] = $this->headerStream;
            parent::__construct($wsdl, $options);

        }

    }

    /**
     * @return mixed|object|Result\ResultInterface|string
     */
    public function __soapCall(
        string $name, array $args,
        ?array $options = null,
        $inputHeaders = null,
        &$outputHeaders = null
    ): mixed
    {
        if (str_starts_with($name, 'pixi')) {

            $vars = array();

            $uri = $this->uri ?? '';

            if (isset($arguments[0]) and is_array($arguments[0]) and count($arguments[0]) > 0) {

                foreach ($arguments[0] as $key => $val) {

                    $vars[] = new \SoapVar($val, null, '', '', $key, $uri);
                }
            }

            $context = array_merge(
                $this->streamContext,
                [
                    'http' => [
                        'header' => 'xapp: ' . Environment::getAppId() . "\r\n" .
                            'soapaction: "' . $uri . $name . '"' . "\r\n",
                    ],
                ]
            );

            stream_context_set_option($this->headerStream, $context);

            $this->content = $this->getResultObject();

            $result = parent::__soapCall($name, $vars, $options, $inputHeaders, $outputHeaders);

            $this->content->setResultSet($result);

            return $this->content;

        }

        return parent::__soapCall($name, $args, $options, $inputHeaders, $outputHeaders);
    }

    /**
     * @param string $request
     * @param string $location
     * @param string $action
     * @param int $version
     * @param int $oneWay
     * @return string
     */
    public function __doRequest(
        string $request,
        string $location,
        string $action,
        int $version,
        bool $oneWay = false
    ): string
    {
        if ($this->transportObject) {

            $transport = $this->getTransportObject();
            $transport->setOptions($this->clientOptions);
            $this->content->setResultSet($transport->__doRequest($request, $location, $action, $version));

            return '';

        } else {

            return parent::__doRequest($request, $location, $action, $version);

        }
    }

    /**
     *
     * @param \Pixi\API\Soap\Options $options
     *            Options for the soap client.
     */
    public function setPixiOptions($options): void
    {
        $this->__construct(null, $options->getOptions());
    }

    /**
     * Clear the the content variable of the object.
     * Cleaning up memory after big queries.
     */
    public function clearContent()
    {
        $this->content = null;
    }

    /**
     * @return object|string
     */
    public function getResultObject()
    {
        if (is_string($this->resultObject)) {
            $this->resultObject = new $this->resultObject;
        }

        return $this->resultObject;
    }

    /**
     * @param $resultObject
     * @return $this
     */
    public function setResultObject($resultObject)
    {
        $this->resultObject = $resultObject;
        return $this;
    }

    /**
     * @param $object
     * @return $this
     */
    public function setTransportObject($object)
    {
        $this->transportObject = $object;
        return $this;
    }

    /**
     * @return object|string
     */
    public function getTransportObject()
    {
        if (is_string($this->transportObject)) {
            $this->transportObject = new $this->transportObject;
        }

        return $this->transportObject;
    }
}
