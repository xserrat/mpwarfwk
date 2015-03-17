<?php
/**
 * Created by PhpStorm.
 * User: xserrat
 * Date: 17/03/15
 * Time: 17:24
 */

namespace Mpwarfwk\Component\Http;


abstract class Response implements ResponseInterface{
    const HTTP_OK = 200;
    const HTTP_NOT_FOUND = 404;

    protected $content;
    protected $status;

    public function __construct($content, $status = self::HTTP_OK){
        $this->content = $content;
        $this->status = $status;
    }

    abstract public function send();
}