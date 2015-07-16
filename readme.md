# Lib: Soap

-----
The Soap library is based on the native Soap client that is available in PHP version 5.0 an upper. This library is based on the syntax that was used in the Apps-Framework on pixi* but is now decoupled and can be used in any other project as well.

## Quick Start

-----

To run the example application, you must first install the development dependencies via composer. From the root `Lib - Soap`, run:

	$ php composer install

After including the `autoload.php` into your project you can start using the library.

To properly initialize the library with your pixi* API you can use following code snipped:
```
:::PHP

<?php

require __DIR__.'/../vendor/autoload.php';

$username = 'pixiDB'; // Your pixi database
$password = 'scurepsswd'; // Your API password
$uri = 'https://leonidas.api.madgeniuses.net/pixiAPP/'; // Enpoint of your API
$location = 'https://leonidas.api.madgeniuses.net/pixiAPP/'; // if your uri is differend from the endpoint location should be added and uri corrected

$options = new Pixi\API\Soap\Options($username, $password, $uri, $location);
$options->allowSelfSigned(true); // if the certificate is self signed

$soapClient = new \Pixi\API\Soap\Client(null, $options->getOptions());

?>
```

API calls are executed trough the magic method `__call`, so you can directly call your desired API call.
```
:::PHP
<?php
$request = $soapClient->pixiGetShops();
$response = $request->getResultSet();

print_r($response);
?>
```

> **NOTE:** For more details visit the library [wiki](https://bitbucket.org/pixi_software/pixi-sdk-soap/wiki/Home)