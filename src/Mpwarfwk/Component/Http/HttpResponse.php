<?php
/**
 * Created by PhpStorm.
 * User: xserrat
 * Date: 17/03/15
 * Time: 17:21
 */

namespace Mpwarfwk\Component\Http;


class HttpResponse extends Response{

    public function send(){
        if($this->status !== Response::HTTP_OK){
            header('HTTP/1.0 404 Not Found');
        }
        echo $this->content;
    }
}