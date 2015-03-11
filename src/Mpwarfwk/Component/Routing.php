<?php

namespace Mpwarfwk\Component;


class Routing {

    public function __construct(){
        //TODO: Include config routes
        echo "Routing<br>";
    }

    public function getRouteController($requestUri){
        //TODO: Return controller namespace of the request.
        echo "Request URI:$requestUri<br>";
        echo "REGEX RESULT: ".preg_replace("/.(.+)/", "\\1", $requestUri);
        exit;
        return "\Controllers\HomeController";
    }
}