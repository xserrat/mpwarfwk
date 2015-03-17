<?php

namespace Mpwarfwk\Component\Template;

use Mpwarfwk\Component\Bootstrap;

class Template {

    const TWIG_ENGINE = 'twig';
    const SMARTY_ENGINE = 'smarty';

    private $engineTemplate;


    public function __construct(TemplateInterface $template){
        switch($this->engineTemplate){
            case self::TWIG_ENGINE:
                return new TwigTemplate($template);
            case self::SMARTY_ENGINE:
                return new SmartyTemplate($template);
            default:
                return new TwigTemplate($template);
        }
    }

    public function setPathViews(){
        $this->engineTemplate = Bootstrap::getApplicationConfig()['template-engine'];
    }
}