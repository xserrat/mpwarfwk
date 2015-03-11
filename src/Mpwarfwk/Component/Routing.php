<?php

namespace Mpwarfwk\Component;


class Routing {

    public function __construct(){
        //TODO: Include config routes
        echo "Routing<br>";
    }

    public function getRouteController($requestUri){
        //TODO: Return controller namespace of the request.
        return "\Controllers\HomeController";
    }
}