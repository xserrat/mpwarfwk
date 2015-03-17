<?php

namespace Mpwarfwk\Component\Http;

class JsonResponse extends Response{

    public function send(){
        if($this->status !== Response::HTTP_OK){
            header('HTTP/1.0 404 Not Found');
        }
        header('Content-Type: application/json');
        if(!is_array($this->content)){
            $this->content = array($this->content);
        }
        echo json_encode($this->content);
    }
}