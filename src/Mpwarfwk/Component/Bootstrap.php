<?php

namespace Mpwarfwk\Component;

use Mpwarfwk\Component\Caching\DiskCache;
use Mpwarfwk\Component\Caching\MemoryCache;
use Mpwarfwk\Component\Http\HttpResponse;
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
        $this->cache = new MemoryCache();
        //$this->cache = new DiskCache();
    }

    public function run(){
        $route = $this->routing->getRouteController($this->request);
        $response = $this->executeController($route);
        return $response;
    }

    private function executeController(Route $route){
        $controllerNamespace = $route->getControllerNamespace();
        $controller = new $controllerNamespace();

        $hasControllerCache = $controller->enableCache;

        if($hasControllerCache){
            $paramsDefinition = $controller->getCacheDefinition($controllerNamespace, $route->getAction(), $this->request);
            $cache = $this->getCache($paramsDefinition);
            if($cache){
                return $cache;
            } else{
                //Check if controller has ControllerAbstract extended to set container object
                $controller = $this->injectContainer($controller);
                $response = $controller->{$route->getAction()}($this->request);
                $this->saveCache($paramsDefinition, $response);
            }
        } else{
            //Check if controller has ControllerAbstract extended to set container object
            $controller = $this->injectContainer($controller);
            $response = $controller->{$route->getAction()}($this->request);
        }
        return $response;
    }

    private function getCache($parameters){
        $keyName = $this->cache->getKeyName($parameters);
        return $this->cache->get($keyName);
    }

    private function saveCache($parameters, $content){
        $keyName = $this->cache->getKeyName($parameters);
        $cacheExpirationSeconds = static::getApplicationConfig()['cache']['controller-expiration'];
        $this->cache->set($keyName, $content, $cacheExpirationSeconds);
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