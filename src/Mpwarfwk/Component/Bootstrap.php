<?php

namespace Mpwarfwk\Component;

use Mpwarfwk\Component\Http\Request,
    Mpwarfwk\Component\Session\Session,
    Mpwarfwk\Component\Routing\Route,
    Mpwarfwk\Component\Routing\Routing;
use Mpwarfwk\Controller\ControllerAbstract;
use Mpwarfwk\FileParser\YamlFileParser;
use Mpwarfwk\Component\Container\Container;

class Bootstrap {

    const CONFIG_FILE_NAME = "config.yaml";
    const PROD_ENVIRONMENT = 'PROD';
    const DEV_ENVIRONMENT = 'DEV';

    private $routing;
    private $request;
    private $container;

    public function __construct(){
        $this->request = new Request(new Session());
        $this->routing = new Routing();
        $this->container = new Container();
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

        //Check if controller has ControllerAbstract extended to set container object
        $controller = $this->injectContainer($controller);

        $response = $controller->{$route->getAction()}($this->request);
        return $response;
    }

    private function injectContainer($controller){
        if(get_parent_class($controller) === 'Mpwarfwk\Controller\ControllerAbstract'){
            $controller->setContainer($this->container);
        }
        return $controller;
    }

    public static function getRootApplicationPath(){
        return preg_replace('/(.+)\/.+/', '\\1', Request::$documentRoot);
    }

    public static function getApplicationConfig(){
        $fileParser = new YamlFileParser(static::getRootApplicationPath().'/config/'.self::CONFIG_FILE_NAME);
        return $fileParser->getFileData();
    }

    public static function getEnvironment(){
        $entryScript = preg_replace('/.+\/(.+).php/', '\\1', Request::$scriptFilename);
        if($entryScript === 'index'){
            return self::PROD_ENVIRONMENT;
        } elseif($entryScript === 'index_dev'){
            return self::DEV_ENVIRONMENT;
        }
        return null;
    }
}