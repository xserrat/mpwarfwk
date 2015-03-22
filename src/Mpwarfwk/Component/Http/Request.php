<?php

namespace Mpwarfwk\Component\Http;

use Mpwarfwk\Component\Session\Session;

class Request {

    const METHOD_POST = 'POST';

    protected $uri;
    protected $baseUri;
    protected $paramsUri;
    protected $method;
    public $getParams;
    public $postParams;
    protected $files;
    protected $cookies;
    protected $server;
    public $session;

    static $documentRoot;
    static $scriptFilename;

    public function __construct(Session $session){

        $this->session = $session;
        $this->initializeFromGlobals();

        //To bound the usage of the superglobals, I put to empty array
        $_GET = array();
        $_POST = array();
        $_COOKIE = array();
        $_SERVER = array();
        self::$documentRoot = $this->server['DOCUMENT_ROOT'];
        self::$scriptFilename = $this->server['SCRIPT_FILENAME'];
    }

    private function initializeFromGlobals(){
        $this->uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH); //To avoid GET params
        $this->processingUri();
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->getParams = new Parameters($_GET);
        $this->postParams = new Parameters($_POST);
        $this->files = $_FILES;
        $this->cookies = $_COOKIE;
        $this->server = $_SERVER;
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

    public function setCookie($key, $value, $expire = null){
        if(array_key_exists($key, $this->cookies)){
            $_COOKIE[$key] = $value;
            $this->cookies[$key] = $value;
        } else{
            if($expire = null){
                $expire = time() + 3600;
            }
            setcookie($key, $value, $expire);
        }
    }

    public function getContentType(){
        return $this->server['CONTENT_TYPE'];
    }

    private function processingUri(){
        $uriArray = explode('/', $this->uri);
        array_shift($uriArray); //Extract empty element from array
        $this->baseUri = '/' . array_shift($uriArray);
        $this->paramsUri = !empty($uriArray)?$uriArray:null; //Get the last elements from uri as parameters
    }

    public function getBaseUri(){
        return $this->baseUri;
    }

    public function getParamsUri(){
        return $this->paramsUri;
    }

    public function getParamUri($key){
        return $this->paramsUri[$key];
    }
}