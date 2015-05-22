<?php

namespace Mpwarfwk\Controller;

use Mpwarfwk\Component\Container\Container;
use Mpwarfwk\Component\Http\Request;
use Mpwarfwk\Component\Container\ContainerAbstract;
use Mpwarfwk\Component\Template\SmartyTemplate;
use Mpwarfwk\Component\Template\TwigTemplate;

abstract class ControllerAbstract extends ContainerAbstract{

    public $cache = false;

    public function forward($controller, $action, Request $request){
        $contr = new $controller();
        if(get_parent_class($contr) === 'Mpwarfwk\Controller\ControllerAbstract'){
            $contr->setContainer(new Container());
        }
        return $contr->{$action}($request);
    }

    public function get($id){
        return $this->container->get($id);
    }

    public function renderTwigView($template, array $params = array()){
        /**
         * @var $twig TwigTemplate
         */
        $twig = $this->container->get('templating-twig');
        return $twig->render($template, $params);
    }

    public function renderSmartyView($template, array $params = array()){
        /**
         * @var $smarty SmartyTemplate
         */
        $smarty = $this->container->get('templating-smarty');
        return $smarty->render($template, $params);
    }

    public function getCacheDefinition($controllerNamespace, $methodName, Request $request){
        $parameters = array(
            'controller' => $controllerNamespace,
            'method' => $methodName
        );
        return $parameters;
    }
}