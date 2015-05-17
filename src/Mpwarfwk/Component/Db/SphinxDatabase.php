<?php

namespace Mpwarfwk\Component\Db;

use Foolz\SphinxQL\Connection;

class SphinxDatabase implements DatabaseInterface{

    private $sphinx;

    public function __construct($host = 'localhost', $port = 9306){
        $this->sphinx = new Connection();
        $this->setConfig($host, $port);
    }

    public function setConfig($host = 'localhost', $port = 9306){
        $this->sphinx->setParams(array('host' => $host, 'port' => $port));
    }

    public function getConnection(){
        return $this->sphinx;
    }
}