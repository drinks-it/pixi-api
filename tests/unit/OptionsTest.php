<?php

namespace Pixi\API\Soap\Tests;

use Pixi\API\Soap\Options;

class OptionsTest extends \PHPUnit_Framework_TestCase
{
    private $options;

    protected function setUp()
    {
        $this->options = new Options('login','password','uri');
    }

    protected function tearDown()
    {
        $this->options = null;
    }

    /**
     * @expectedException ErrorException
     */
    public function testConstructErrorException()
    {
        new Options();
    }

    public function testContruct()
    {
        $optionsProperty = array(
            'user_agent' => 'pixi API Client 0.1',
            'soap_version' => SOAP_1_2,
            'login' => 'username',
            'password' => 'password',
            'uri' => 'uri',
            'location' => 'location',
            'namespace' => 'location',
            'trace' => true
        );

        $reflectionClass = new \ReflectionClass('Pixi\API\Soap\Options');
        $property = $reflectionClass->getProperty('options');
        $property->setAccessible(true);

        $options = new Options('username', 'password', 'uri', 'location', true);

        $this->assertSame($optionsProperty, $property->getValue($options));
    }

    /**
     * @runInSeparateProcess
     */
    public function testMethodChaining()
    {
        $this->assertInstanceOf('Pixi\API\Soap\Options', $this->options->setLogin(1)->setPassword(1)->setUri(1));
    }

    public function testSetLogin()
    {
        $reflectionClass = new \ReflectionClass('Pixi\API\Soap\Options');
        $property = $reflectionClass->getProperty('options');
        $property->setAccessible(true);

        $options = new Options('username', 'password', 'uri');
        $options->setLogin('newUsername');
        $properties = $property->getValue($options);

        $this->assertSame('newUsername', $properties['login']);
    }

    public function testSetPassword()
    {
        $reflectionClass = new \ReflectionClass('Pixi\API\Soap\Options');
        $property = $reflectionClass->getProperty('options');
        $property->setAccessible(true);

        $options = new Options('username', 'password', 'uri');
        $options->setPassword('newPassword');
        $properties = $property->getValue($options);

        $this->assertSame('newPassword', $properties['password']);
    }

    public function testSetUri()
    {
        $reflectionClass = new \ReflectionClass('Pixi\API\Soap\Options');
        $property = $reflectionClass->getProperty('options');
        $property->setAccessible(true);

        $options = new Options('username', 'password', 'uri');
        $options->setUri('newUri');
        $properties = $property->getValue($options);

        $this->assertSame('newUri', $properties['uri']);
    }

    public function testGetOptions()
    {
        $expectedArray = array(
            'user_agent' => 'pixi API Client 0.1',
            'soap_version' => SOAP_1_2,
            'login' => 'loginname',
            'password' => 'mysecretPassword',
            'uri' => '/index/Overview',
            'location' => '/index/Overview'
        );

        $options = new Options('loginname', 'mysecretPassword', '/index/Overview');
        $optionsArray = $options->getOptions();
        $this->assertSame($expectedArray, $optionsArray);

        $options->setLogin('newlogin');
        $optionsArray = $options->getOptions();
        $this->assertNotSame($expectedArray, $optionsArray);

        $options->setPassword('mynewpassword');
        $optionsArray = $options->getOptions();
        $this->assertNotSame($expectedArray, $optionsArray);

        $options->setUri('/index/NewUri');
        $optionsArray = $options->getOptions();
        $this->assertNotSame($expectedArray, $optionsArray);
    }

    public function testSetOptions()
    {
        $this->options->setOptions(array('newOption' => 'newValue'));
        $options = $this->options->getOptions();

        $this->assertTrue(array_key_exists('newOption', $options));
        $this->assertSame('newValue', $options['newOption']);

        $this->options->setOptions(array('user_agent' => 'newAgent'));
        $options = $this->options->getOptions();

        $this->assertSame('newAgent', $options['user_agent']);
    }
}