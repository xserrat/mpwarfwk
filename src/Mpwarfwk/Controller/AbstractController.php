<?php

namespace Mpwarfwk\Controller;

use Mpwarfwk\Component\Request;
use Mpwarfwk\Template\Template;

class AbstractController {

    public function forward($controller, $action, Request $request){}
    public function redirect($url, $statusCode = 302){

    }
    public function renderView($view, array $params){


    }
    public function get($paramName){}
    public function has($paramName){}
    public function getRequest(){}
}