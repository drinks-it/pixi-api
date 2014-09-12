<?php
/**
 * Created by PhpStorm.
 * User: cschmitti
 * Date: 12.09.14
 * Time: 13:51
 */

namespace Pixi\API\Soap;


class SoapExceptions extends \Exception {

    public function __construct($message, $code = 0) {
        parent::__construct($message, $code);
    }
} 