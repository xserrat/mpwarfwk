<?php
/**
 * Created by PhpStorm.
 * User: xserrat
 * Date: 17/03/15
 * Time: 16:15
 */

namespace Mpwarfwk\Component\Routing;


class Route {

    private $controllerNamespace;
    private $action;
    private $parameters;

    public function __construct($controllerNamespace, $action, $parameters){
        $this->controllerNamespace = $controllerNamespace;
        $this->action = $action;
        $this->parameters = $parameters;
    }

    public function getControllerNamespace(){
        return $this->controllerNamespace;
    }

    public function getAction(){
        return $this->action;
    }

    public function getParameters(){
        return $this->parameters;
    }

}