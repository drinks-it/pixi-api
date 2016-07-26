<?php
namespace Pixi\API\Soap;

class Options
{

    protected $options = array(
        'user_agent'   => 'pixi API Client 0.1',
        'soap_version' => SOAP_1_2,
    );

    public function __construct($login, $password, $uri, $location = null, $trace = false)
    {
        $this->setLogin($login)
            ->setPassword($password)
            ->setUri($uri)
            ->setLocation(empty($location) ? $uri : $location)
            ->setTrace($trace);
        return $this;
    }

    public function setStreamContextOptions($options)
    {
        $this->options['stream_context'] = $options;
        return $this;
    }

    public function allowSelfSigned($allow = true)
    {
        $this->setStreamContextOptions(
            ['ssl' => [
                'allow_self_signed' => $allow,
                'verify_peer'       => false,
                'verify_peer_name'  => false
            ]
        ]);
        
        return $this;
    }

    public function setLogin($login)
    {
        $this->options['login'] = $login;
        return $this;
    }

    public function setPassword($password)
    {
        $this->options['password'] = $password;
        return $this;
    }

    public function setUri($uri)
    {
        $this->options['uri'] = $uri;
        return $this;
    }

    public function setLocation($location)
    {
        $this->options['location'] = $location;
        return $this;
    }

    public function setTrace($trace)
    {
        $this->options['trace'] = $trace;
        return $this;
    }

    public function getOptions()
    {
        return $this->options;
    }

    public function setOptions(array $options)
    {
        $this->options = array_merge($this->options, $options);
    }

    public function setSslMethod($sslMethod)
    {
        $this->options['ssl_method'] = $sslMethod;
        return $this;
    }

}
