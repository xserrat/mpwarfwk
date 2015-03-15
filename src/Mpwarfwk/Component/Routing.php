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
        $result = $this->getProcessedRoute($route);
        if(!is_array($result)){

        }
        if(!array_key_exists($route, $this->configRoutes)){
            return false;
        }
        $controllerNamespace = $this->configRoutes[$route]['controller'];
        $action = $this->configRoutes[$route]['action'];
        return array($controllerNamespace, $action);
    }

    private function getProcessedRoute($route){
        if($route === '/'){
            return $route;
        }
        $baseRoute = preg_replace('/(\/[a-z]+)(\/[a-z]+)*(\/.+)/', '\\1', $route);
        $action = preg_replace('/(\/[a-z]+)(\/[a-z]+)*(\/.+)/', '\\2', $route);
        $rawParams = preg_replace('/(\/[a-z]+)(\/[a-z]+)*(\/.+)/', '\\3', $route);
        echo "BaseRoute: $baseRoute<br>";
        echo "Action: $action<br>";
        echo "rawParams: $rawParams<br>";
        exit;

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