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

        if(!$classController){
            //TODO: Throw an exception!
            echo "Wrong routes in routes.json";die;
        }

        $controller = new $classController();
    }

    public static function getRootApplicationPath(){
        return preg_replace("/(.+)\/.+/", "\\1", $_SERVER['DOCUMENT_ROOT']);
    }
}