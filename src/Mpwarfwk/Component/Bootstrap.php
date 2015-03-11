<?php

namespace Mpwarfwk\Component;

class Bootstrap {

    public function __construct(){
        echo "Bootstrap<br>";
    }

    public function run(){
        $routing = new Routing();
        $request = new Request();
        $requestUri = $request->getRequestUri();
        $classController = $routing->getRouteController($requestUri);

        $controller = new $classController();
    }
}