<?php

namespace Mpwarfwk\Component;

class Bootstrap {

    const CONFIG_FILE_NAME = "config.json";

    public function __construct(){
        echo "Bootstrap<br>";
    }

    public function run(){
        $routing = new Routing();
        $request = new Request();
        $requestUri = $request->getRequestUri();
        list($classController, $action) = $routing->getRouteController($requestUri);

        if(!$classController || !$action){
            //TODO: Throw an exception!
            echo "Wrong routes in routes.json";die;
        }

        $controller = new $classController();
        $controller->{$action}();
    }

    public static function getRootApplicationPath(){
        return preg_replace("/(.+)\/.+/", "\\1", $_SERVER['DOCUMENT_ROOT']);
    }

    public static function getApplicationConfig(){
        return json_decode(file_get_contents(static::getRootApplicationPath()."/config/".self::CONFIG_FILE_NAME), true);
    }
}