<?php

namespace Mpwarfwk\Component\Http;

use Mpwarfwk\Component\Session\Session;

class Request {

    const METHOD_POST = 'POST';

    protected $uri;
    protected $baseUri;
    protected $actionUri;
    protected $paramsUri;
    protected $method;
    public $getParams;
    public $postParams;
    protected $files;
    protected $cookies;
    protected $server;
    protected $session;

    public function __construct(Session $session){

        $this->session = $session;
        $this->initializeFromGlobals();

        //To bound the usage of the superglobals, I put to empty array
        $_GET = array();
        $_POST = array();
        $_COOKIE = array();
        $_SERVER = array();
    }

    private function initializeFromGlobals(){
        $this->uri = $_SERVER['REQUEST_URI'];
        $this->processingUri();
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->getParams = new Parameters($_GET);
        $this->postParams = new Parameters($_POST);
        $this->files = $_FILES;
        $this->cookies = $_COOKIE;
        $this->server = $_SERVER;
        $this->session = $_SESSION;
    }

    public function getUri(){
        return $this->uri;
    }

    public function getMethod(){
        $this->method;
    }

    public function getFileInfo($fileKey){
        if(!array_key_exists($fileKey, $this->files)){
            return null;
        }
        return $this->files[$fileKey];
    }

    public function getCookieValue($key){
        if(!array_key_exists($key, $this->cookies)){
            return null;
        }
        return $this->cookies[$key];
    }

    public function hasCookie($cookieKey){
        return array_key_exists($cookieKey, $this->cookies);
    }

    public function getContentType(){
        return $this->server['CONTENT_TYPE'];
    }

    private function processingUri(){
        $uriArray = explode('/', $this->uri);
        array_shift($uriArray); //Extract empty element from array
        $this->baseUri = array_shift($uriArray);
        $this->actionUri = array_shift($uriArray);
        $this->paramsUri = !empty($uriArray)?$uriArray:null; //Get the last elements from uri as parameters
    }

    public function getBaseUri(){
        return $this->baseUri;
    }

    public function getActionUri(){
        return $this->actionUri;
    }

    public function getParamsUri(){
        return $this->paramsUri;
    }


}