<?php

namespace Mpwarfwk\Component;

use Mpwarfwk\FileParser\JsonFileParser,
    Mpwarfwk\FileParser\YamlFileParser;

class Routing {

    private $configRoutes;

    /**
     * $parserClass FileParser
     */
    public function __construct(){
        $parserClass = $this->getRoutesConfig();
        $this->configRoutes = $parserClass->getFileData();
    }

    public function getRouteController($route){
        //TODO: Return controller namespace of the request.
        if(!array_key_exists($route, $this->configRoutes)){
            return false;
        }
        list($controllerNamespace, $action) = $this->configRoutes[$route];
        return array("controllerNamespace" => $controllerNamespace, "action" => $action);
    }

    private function getRoutesConfig(){
        if(Bootstrap::getApplicationConfig()['routing-format'] === 'yaml'){
            return new YamlFileParser();
        }
        return new JsonFileParser();
    }
}