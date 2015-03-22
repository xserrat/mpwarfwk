<?php

namespace Mpwarfwk\Component\Db;

use PDO;

class PDODatabase implements DatabaseInterface{

    private $pdoConnection;

    public function __construct($host, $dbName, $username, $password){
        $dsn = "mysql:host=$host;dbname=$dbName";
        $this->pdoConnection = new PDO($dsn, $username, $password);
        $this->pdoConnection->setAttribute( PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC );
    }

    public function getConnection(){
        return $this->pdoConnection;
    }
}