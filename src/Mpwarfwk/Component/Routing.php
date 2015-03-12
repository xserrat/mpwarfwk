<?php

namespace Mpwarfwk\Component;


class Routing {

    private $configRoutes;

    public function __construct(){
        $jsonRoutesConfig = file_get_contents(Bootstrap::getRootApplicationPath() . "/config/routes.json");
        $this->configRoutes = json_decode($jsonRoutesConfig, true);
    }

    public function getRouteController($route){
        //TODO: Return controller namespace of the request.

        if(!in_array($route, $this->configRoutes)){
            return false;
        }
        $controllerNamespace = $this->configRoutes[$route];
        return $controllerNamespace;
    }
}