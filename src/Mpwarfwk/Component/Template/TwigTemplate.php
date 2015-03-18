<?php
/**
 * Created by PhpStorm.
 * User: xserrat
 * Date: 14/03/15
 * Time: 11:59
 */

namespace Mpwarfwk\Component\Template;


class TwigTemplate implements TemplateInterface{

    private $twig;

    public function __construct($templatesPath){
        $loader = new \Twig_Loader_Filesystem($templatesPath);
        $twig = new \Twig_Environment($loader, array());
        $this->twig = $twig;
    }

    public function render($template, array $params){
        return $this->twig->render($template, $params);
    }
}