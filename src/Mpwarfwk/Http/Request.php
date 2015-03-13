<?php

namespace Mpwarfwk\Http;

class Request {

    const METHOD_POST = 'POST';

    protected $uri;
    protected $method;
    protected $params;
    protected $files;
    protected $cookies;
    protected $server;
    protected $session;

    public function __construct($uri = null, $method = null, array $params = array(), $files = array(), $cookies = array(), $server = array()){
        $this->uri = $uri;
        $this->method = $method;
        $this->params = $params;
        $this->files = $files;
        $this->cookies = $cookies;
        $this->server = $server;
    }

    public function initializeFromGlobals(){
        $this->uri = $_SERVER['REQUEST_URI'];
        $this->method = $_SERVER['REQUEST_METHOD'];
        if($this->method === self::METHOD_POST){
            $this->params = $_POST;
        } else{
            $this->params = $_GET;
        }
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

    public function getParams(){
        return $this->params;
    }

    public function getParam($key){
        if(!array_key_exists($key, $this->params)){
            return null;
        }
        return $this->params[$key];
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

    public function getSessionValue($sessionKey){
        if(!array_key_exists($sessionKey, $this->session)){
            return null;
        }
        return $this->session[$sessionKey];
    }

    public function hasSession($sessionKey){
        return array_key_exists($sessionKey, $this->session);
    }

    public function getContentType(){
        return $this->server['CONTENT_TYPE'];
    }
}