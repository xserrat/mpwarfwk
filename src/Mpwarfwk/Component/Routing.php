<?php

namespace Mpwarfwk\Component;

use Mpwarfwk\FileParser\JsonFileParser,
    Mpwarfwk\FileParser\YamlFileParser;

class Routing {

    const YAML_CONFIG_FILE = 'routes.yaml';
    const JSON_CONFIG_FILE = 'routes.json';

    private $configRoutes;

    /**
     * $parserClass FileParser
     */
    public function __construct(){
        $this->configRoutes = $this->getRoutesConfig();
    }

    public function getRouteController($route){
        //TODO: Return controller namespace of the request.
        if(!array_key_exists($route, $this->configRoutes)){
            return false;
        }
        $controllerNamespace = $this->configRoutes[$route]['controller'];
        $action = $this->configRoutes[$route]['action'];
        echo $controllerNamespace;
        echo $action;exit;
        return array($controllerNamespace, $action);
    }

    private function getRoutesConfig(){
        if(Bootstrap::getApplicationConfig()['routing-format'] === 'yaml'){
            $routesFile = $this->getRoutingFilePath(self::YAML_CONFIG_FILE);
            $fileParser = new YamlFileParser($routesFile);
        } else{
            $routesFile = $this->getRoutingFilePath(self::JSON_CONFIG_FILE);
            $fileParser = new JsonFileParser($routesFile);
        }
        return $fileParser->getFileData();
    }

    private function getRoutingFilePath($filename){
        return Bootstrap::getRootApplicationPath() . "/config/" . $filename;
    }
}