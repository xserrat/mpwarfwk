<?php

namespace Mpwarfwk\Component\Routing;

use Mpwarfwk\FileParser\JsonFileParser,
    Mpwarfwk\FileParser\YamlFileParser,
    Mpwarfwk\FileParser\PhpFileParser;
use Mpwarfwk\Component\Http\Request;
use Mpwarfwk\Component\Bootstrap;
use Mpwarfwk\Exception\RouteNotFoundException;

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

    public function getRouteController(Request $request)
    {
        if (!array_key_exists($request->getBaseUri(), $this->configRoutes)) {
            throw new RouteNotFoundException("The route '{$request->getBaseUri()}' introduced doesn't exist");
        }
        $controllerNamespace = $this->configRoutes[$request->getBaseUri()]['controller'];
        $action = $this->configRoutes[$request->getBaseUri()]['action'];
        return new Route($controllerNamespace, $action);
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