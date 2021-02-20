<?php

namespace Mpwarfwk\Component\Http;


class Parameters {

    private $parameters;

    public function __construct(array $parameters = array()){
        $this->parameters = $parameters;
    }

    public function getParams(){
        return $this->parameters;
    }

    public function getInt($key){
        if(array_key_exists($key, $this->parameters) && filter_var($this->parameters[$key], FILTER_SANITIZE_NUMBER_INT)){
            return $this->parameters[$key];
        }
        return null;
    }

    public function getString($key){
        if(array_key_exists($key, $this->parameters) && filter_var($this->parameters[$key], FILTER_SANITIZE_STRING)){
            return $this->parameters[$key];
        }
        return null;
    }

    public function getParam($key){
        if(!array_key_exists($key, $this->parameters)){
            return null;
        }
        return $this->parameters[$key];
    }
}