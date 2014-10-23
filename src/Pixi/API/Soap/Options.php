<?php
namespace Pixi\API\Soap;

class Options
{

    private $options = array(
        'user_agent' => 'pixi API Client 0.1',
        'soap_version' => SOAP_1_2
    );

    public function __construct($login, $password, $uri, $namespace = null, $trace = false)
    {
        $this->setLogin($login)->setPassword($password)->setUri($uri)->setNamespace(empty($namespace) ? $uri : $namespace)->setTrace($trace);
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
        $this->options['location'] = $uri;
        return $this;
    }

    public function getOptions()
    {
        return $this->options;
    }
    
    public function setNamespace($namespace) {
    	$this->options['namespace'] = $namespace;
    	return $this;
    }

    public function setOptions(array $options)
    {
        $this->options = array_merge($this->options, $options);
    }
    
    public function setTrace($trace)
    {
        $this->options['trace'] = $trace;
        return $this;
    }

}
