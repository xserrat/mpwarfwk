<?php

namespace Mpwarfwk\Component\Template;

use Mpwarfwk\Component\Bootstrap;

class TwigTemplate implements TemplateInterface{

    protected $twig;

    public function __construct($templatesPath = null){
        if(is_null($templatesPath)){
            $defaultPath = Bootstrap::getApplicationConfig()['default-twig-templates-path'];
            $templatesPath = Bootstrap::getRootApplicationPath() . '/' . $defaultPath;
        }
        $loader = new \Twig_Loader_Filesystem($templatesPath);
        $cacheTwigConfig = Bootstrap::getApplicationConfig()['cache-twig'];
        $twig = new \Twig_Environment($loader, $cacheTwigConfig);
        $this->twig = $twig;
    }

    public function render($template, array $params = array()){
        return $this->twig->render($template, $params);
    }
}