<?php

namespace Mpwarfwk\Component;

use Mpwarfwk\Component\Http\Request,
    Mpwarfwk\Component\Session\Session,
    Mpwarfwk\Component\Routing\Route,
    Mpwarfwk\Component\Routing\Routing;

class Bootstrap {

    const CONFIG_FILE_NAME = "config.json";
    const PROD_ENVIRONMENT = 'PROD';
    const DEV_ENVIRONMENT = 'DEV';

    private $routing;
    private $request;

    public function __construct(){
        $this->routing = new Routing();
        $this->request = new Request(new Session());
    }

    public function run(){
        $route = $this->routing->getRouteController($this->request);
        if(is_null($route)){
            //TODO: Throw an exception!
            echo "Wrong routes in routes.json";die;
        }
        $response = $this->executeController($route);
        return $response;
    }

    private function executeController(Route $route){
        $controllerNamespace = $route->getControllerNamespace();
        $controller = new $controllerNamespace();
        $response = $controller->{$route->getAction()}($this->request);
        return $response;
    }

    public static function getRootApplicationPath(){
        return preg_replace('/(.+)\/.+/', '\\1', $_SERVER['DOCUMENT_ROOT']);
    }

    public static function getApplicationConfig(){
        return json_decode(file_get_contents(static::getRootApplicationPath().'/config/'.self::CONFIG_FILE_NAME), true);
    }

    public static function getEnvironment(){
        $entryScript = preg_replace('/.+\/(.+).php/', '\\1', $_SERVER['SCRIPT_FILENAME']);
        if($entryScript === 'index'){
            return self::PROD_ENVIRONMENT;
        } elseif($entryScript === 'index_dev'){
            return self::DEV_ENVIRONMENT;
        }
        return null;
    }
}