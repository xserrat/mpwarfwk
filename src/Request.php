<?php
/**
 * Created by PhpStorm.
 * User: xserrat
 * Date: 9/03/15
 * Time: 21:52
 */

class Request {

    private $requestUri;

    public function __construct(){
        $this->requestUri = $_SERVER['REQUEST_URI'];
    }

    public function getRequestUri(){
        return $this->requestUri;
    }
}