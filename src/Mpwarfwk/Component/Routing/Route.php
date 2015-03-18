<?php

namespace Mpwarfwk\Component\Routing;


class Route {

    private $controllerNamespace;
    private $action;

    public function __construct($controllerNamespace, $action){
        $this->controllerNamespace = $controllerNamespace;
        $this->action = $action;
    }

    public function getControllerNamespace(){
        return $this->controllerNamespace;
    }

    public function getAction(){
        return $this->action;
    }
}