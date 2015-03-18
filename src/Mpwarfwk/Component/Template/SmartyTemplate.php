<?php

namespace Mpwarfwk\Component\Template;


class SmartyTemplate implements TemplateInterface{

    private $smarty;

    public function __construct(){
        $this->smarty = new \Smarty();
    }

    public function render($template, array $params = array()){
        foreach($params as $key => $value){
            $this->smarty->assign($key, $value);
        }
        return $this->smarty->fetch($template);
    }

}