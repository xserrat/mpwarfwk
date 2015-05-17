<?php

namespace Mpwarfwk\Component\Db;

use Predis\Client;

class RedisDatabase implements DatabaseInterface{

    private $redis;

    public function __construct($scheme = 'tcp', $host = '127.0.0.1', $port = 6379){
        $this->redis = new Client(
            array(
                'scheme' => $scheme,
                'host' => $host,
                'port' => $port
            )
        );
    }

    public function getRedisClient(){
        return $this->redis;
    }

    public function getConnection(){
        return $this->redis->getConnection();
    }

    public function get($key){
        return $this->redis->get($key);
    }

    public function set($key, $value, $expireResolution = null, $expireTTL = null){
        return $this->redis->set($key, $value, $expireResolution, $expireTTL);
    }
}