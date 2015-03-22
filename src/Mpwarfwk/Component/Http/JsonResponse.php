<?php

namespace Mpwarfwk\Component\Http;

class JsonResponse extends Response{

    public function send(){
        if($this->status !== Response::HTTP_OK){
            if(array_key_exists($this->status, Response::$statusTexts)){
                header("HTTP/1.0 {$this->status} ". Response::$statusTexts[$this->status]);
            } else{
                header("HTTP/1.0 404 Not found");
            }
        }
        header('Content-Type: application/json');
        if(!is_array($this->content)){
            $this->content = array($this->content);
        }
        echo json_encode($this->content);
    }
}