<?php

namespace Mpwarfwk\Component;

class Request {

    private $requestUri;

    public function __construct(){
        $this->requestUri = $_SERVER['REQUEST_URI'];
    }

    public function getRequestUri(){
        return $this->requestUri;
    }
}