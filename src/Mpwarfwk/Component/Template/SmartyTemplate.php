<?php

namespace Mpwarfwk\Component\Template;

use Mpwarfwk\Component\Bootstrap;

class SmartyTemplate implements TemplateInterface{

    protected $smarty;
    protected $templatesPath;

    public function __construct($templatesPath = null){
        if(is_null($templatesPath)){
            $defaultPath = Bootstrap::getApplicationConfig()['default-smarty-templates-path'];
            $templatesPath = Bootstrap::getRootApplicationPath() . '/' . $defaultPath;
        }
        $this->templatesPath = $templatesPath;
        $this->smarty = new \Smarty();
    }

    public function render($template, array $params = array()){
        $pathToTemplate = $this->templatesPath . '/' . $template;
        foreach($params as $key => $value){
            $this->smarty->assign($key, $value);
        }
        return $this->smarty->fetch($pathToTemplate);
    }
}