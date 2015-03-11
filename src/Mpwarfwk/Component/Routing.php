<?php

namespace Mpwarfwk\Component;


class Routing {

    private $configRoutes;

    public function __construct(){
        $pathConfigRoutes = $_SERVER['DOCUMENT_ROOT'] . "../config/routes.json";
        $this->configRoutes = json_decode($pathConfigRoutes, true);
    }

    public function getRouteController($requestUri){
        //TODO: Return controller namespace of the request.
        $route = preg_replace("/.(.+)/", "\\1", $requestUri);

        if(!in_array($route, $this->configRoutes)){
            return false;
        }
        $controllerNamespace = $this->configRoutes[$route];
        return $controllerNamespace;
    }
}