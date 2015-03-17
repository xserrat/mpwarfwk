<?php

namespace Mpwarfwk\Component\Routing;

use \Mpwarfwk\FileParser\JsonFileParser,
    \Mpwarfwk\FileParser\YamlFileParser;
use \Mpwarfwk\Component\Http\Request;
use \Mpwarfwk\Component\Bootstrap;
use Mpwarfwk\FileParser\PhpFileParser;

class Routing {

    const YAML_CONFIG_FILE = 'routes.yaml';
    const JSON_CONFIG_FILE = 'routes.json';
    const PHP_CONFIG_FILE = 'routes.php';

    private $configRoutes;

    /**
     * $parserClass FileParser
     */
    public function __construct(){
        $this->configRoutes = $this->getRoutesConfig();
    }

    //TODO: Rehacer getRouteController!!
    public function getRouteController(Request $request){
        //Uri is only '/'
        if(is_null($request->getBaseUri())){
            if(!array_key_exists('/', $this->configRoutes)){
                return null;
            }
            $controllerNamespace = $this->configRoutes['/']['controller'];
            $action = $this->configRoutes['/']['action'];
            return new Route($controllerNamespace, $action, null);
        }

        //Uri only have controller
        if(is_null($request->getActionUri())){
            $route = '/' . $request->getBaseUri();
            if(!array_key_exists($route, $this->configRoutes)){
                return null;
            }
            $controllerNamespace = $controllerNamespace = $this->configRoutes[$route]['controller'];
            $action = $this->configRoutes[$route]['action'];
            return new Route($controllerNamespace, $action, null);
        }

        //Uri only have controller and action
        if(is_null($request->getParamsUri())){
            $route = '/' . $request->getBaseUri() . '/' . $request->getActionUri();
            if(!array_key_exists($route, $this->configRoutes)){
                return null;
            }
            $controllerNamespace = $this->configRoutes[$route]['controller'];

            //If config has defined default action, it has lower priority than action from uri
            $action = $this->getCamelCaseFromHyphen($request->getActionUri());
            return new Route($controllerNamespace, $action, null);
        }

        //En el caso de ahora, se irá a la misma ruta que el caso sin parametros pero se pasaran los parametros
        //TODO: Uri have controller, action and parameters. (Si no se dice nada, se mirará si existe la
        //TODO: ruta con baseUri+actionUri y se pasarán por parámetro los valores.
        $route = '/' . $request->getBaseUri() . '/' . $request->getActionUri();
        if(!array_key_exists($route, $this->configRoutes)){
            return null;
        }
        $controllerNamespace = $this->configRoutes[$route]['controller'];
        $action = $this->getCamelCaseFromHyphen($request->getActionUri());
        $params = array();
        foreach($request->getParamsUri() as $param){
            array_push($params, $this->getCamelCaseFromHyphen($param));
        }
        return new Route($controllerNamespace, $action, $params);
    }

    private function getCamelCaseFromHyphen($string){
        //Conversion from hyphen to camelCase
        $parts = explode('-', $string);
        $parts = array_map('ucfirst', $parts);
        $string = lcfirst(implode('', $parts));
        return $string;
    }

    private function getRoutesConfig(){
        if(Bootstrap::getApplicationConfig()['routing-format'] === 'yaml'){
            $routesFile = $this->getRoutingFilePath(self::YAML_CONFIG_FILE);
            $fileParser = new YamlFileParser($routesFile);
        } elseif(Bootstrap::getApplicationConfig()['routing-format'] === 'json'){
            $routesFile = $this->getRoutingFilePath(self::JSON_CONFIG_FILE);
            $fileParser = new JsonFileParser($routesFile);
        } else{
            $routesFile = $this->getRoutingFilePath(self::PHP_CONFIG_FILE);
            $fileParser = new PhpFileParser($routesFile);
        }
        return $fileParser->getFileData();
    }

    private function getRoutingFilePath($filename){
        return Bootstrap::getRootApplicationPath() . "/config/" . $filename;
    }
}