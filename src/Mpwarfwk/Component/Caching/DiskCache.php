<?php

namespace Mpwarfwk\Component\Caching;

use Mpwarfwk\Component\Bootstrap;

class DiskCache extends Cache{

    public function __construct(){
        $this->cachePath = Bootstrap::getRootApplicationPath() . '/cache/disk';
    }

    public function get($key){
        if(file_exists($this->cachePath . $key)){
            return file_get_contents($this->cachePath . $key);
        }
        return false;
    }

    public function set($key, $value, $ttl = 3600){
        if(!file_exists($this->cachePath)){
            mkdir($this->cachePath);
        }
        $fileContent = array(
            'ttl' => $ttl,
            'content' => $value
        );
        $jsonFileContent = json_encode($fileContent);
        file_put_contents($this->cachePath . $key, $jsonFileContent);
    }

    public function delete($key){
        return unlink($this->cachePath . $key);
    }
}