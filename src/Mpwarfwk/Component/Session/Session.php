<?php

namespace Mpwarfwk\Component\Session;

class Session {

    public function __construct(){
        session_start();
    }

    public function getValue($key){
        if(!array_key_exists($key, $_SESSION)){
            return null;
        }
        return $_SESSION[$key];
    }

    public function setValue($key, $value){
        $_SESSION[$key] = $value;
    }

    public function hasSession($sessionKey){
        return array_key_exists($sessionKey, $_SESSION);
    }
}