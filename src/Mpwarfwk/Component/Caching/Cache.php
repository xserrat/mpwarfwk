<?php

namespace Mpwarfwk\Component\Caching;


abstract class Cache {

    abstract public function set($key, $content, $expiration);

    abstract public function get($key);

    abstract public function delete($key);

    public function getKeyName(array $parameters){
        ksort($parameters);
        $key = implode('_', $parameters);
        $keyHash = md5($key);
        return $keyHash;
    }

    /**
     * Invalidate cache elements without calling delete method
     */
    public function invalidateCacheElement(){

    }
}