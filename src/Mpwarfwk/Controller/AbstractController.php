<?php

namespace Mpwarfwk\Controller;

use Mpwarfwk\Component\Http\Request;
use Mpwarfwk\Component\Container\ContainerAbstract;
use Mpwarfwk\Component\Http\Response;
use Mpwarfwk\Component\Template\SmartyTemplate;
use Mpwarfwk\Component\Template\TwigTemplate;

abstract class ControllerAbstract extends ContainerAbstract{

    public function forward($controller, $action, Request $request){}
    public function redirect($url, $statusCode = Response::HTTP_TEMPORARY_REDIRECT){

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
    //public function getParam($paramName){}
    public function hasParam($paramName){}
    public function getRequest(){}
}