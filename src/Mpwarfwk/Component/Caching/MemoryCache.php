<?php

namespace Mpwarfwk\Component\Caching;

use \Memcache;

class MemoryCache extends Cache{

    private $cache;

    public function __construct($host = 'localhost', $port = 11211){
        $this->cache = new Memcache();
        $this->cache->addserver($host, $port);
    }

    public function set($key, $content, $expiration){
        $this->cache->set($key, $content, MEMCACHE_COMPRESSED, $expiration);
    }

    public function get($key){
        return $this->cache->get($key);
    }

    public function delete($key){
        $this->cache->delete($key);
    }
}