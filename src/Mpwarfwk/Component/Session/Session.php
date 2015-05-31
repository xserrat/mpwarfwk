<?php

namespace Mpwarfwk\Component\Session;

use Mpwarfwk\Component\Db\PDODatabase;
use Mpwarfwk\FileParser\YamlFileParser;

class Session {

    const DB_CONFIG = 'dbconfig.yaml';
    const RANDOM_CHARS_CSRF = 70;
    const LIFE_TIME_SESSION_COOKIE = 600;

    private $csrfKey = 'csrf';
    private $csrfValue;
    private $sessionId;
    private $pdo;

    public function __construct(){
        session_set_cookie_params(self::LIFE_TIME_SESSION_COOKIE);
        session_start();
        $this->sessionId = session_id();
        $this->pdo = new PDODatabase();

        $rootApplicationPath = preg_replace('/(.+)\/.+/', '\\1', $_SERVER['DOCUMENT_ROOT']);
        $fileParser = new YamlFileParser($rootApplicationPath . '/config/' . self::DB_CONFIG);

        $dbConfig = $fileParser->getFileData()['database-user-permisions'];
        $this->pdo->setConfig($dbConfig['host'], $dbConfig['dbname'], $dbConfig['username'], $dbConfig['password']);

        if(!$this->isSessionIdExist($this->sessionId)){
            $this->generateCsrf($this->sessionId);
        }
    }

    public function isSessionIdExist($sessionId){
        $connection = $this->pdo->getConnection();

        $query = <<<SQL
SELECT `phpsessid` FROM `sessions` WHERE phpsessid=:phpSessionId
SQL;
        $stmt = $connection->prepare($query);
        $stmt->bindParam(':phpSessionId', $sessionId, \PDO::PARAM_STR);
        $stmt->execute();
        if($stmt->rowCount() > 0){
            return true;
        }
        return false;
    }

    public function generateCsrf($sessionId){
        $this->csrfValue = md5(openssl_random_pseudo_bytes(self::RANDOM_CHARS_CSRF));

        $connection = $this->pdo->getConnection();

        $query = <<<SQL
INSERT INTO `sessions` (`phpsessid`, `session_key`, `session_value`)VALUES(:phpsessid, :session_key, :session_value)
SQL;
        $stmt = $connection->prepare($query);
        $stmt->bindParam(':phpsessid', $sessionId, \PDO::PARAM_STR);
        $stmt->bindParam(':session_key', $this->csrfKey, \PDO::PARAM_STR);
        $stmt->bindParam(':session_value', $this->csrfValue, \PDO::PARAM_STR);
        $stmt->execute();
    }

    public function getCsrfKey(){
        return $this->csrfKey;
    }

    public function getCsrfValue(){
        return $this->csrfValue;
    }

    public function getValue($key){
        if(!array_key_exists($key, $_SESSION)){
            return null;
        }
        return $_SESSION[$key];
    }

    public function setValue($key, $value){
        $_SESSION[$key] = $value;
    }

    public function hasSession($sessionKey){
        return array_key_exists($sessionKey, $_SESSION);
    }

    public function getSessionId(){
        return $this->sessionId;
    }

    public function destroySession(){
        $query = <<<SQL
DELETE FROM `sessions` WHERE phpsessid=:phpsessid
SQL;
        $connection = $this->pdo->getConnection();
        $stmt = $connection->prepare($query);
        $stmt->bindParam(':phpsessid', $this->sessionId, \PDO::PARAM_STR);
        $stmt->execute();

        $_SESSION = array();
        $_COOKIE = array();
        setcookie('PHPSESSID', null, -1, '/');
        session_destroy();
    }

    public function addKeyValue($key, $value){
        $connection = $this->pdo->getConnection();

        $query = <<<SQL
INSERT INTO `sessions` (`phpsessid`, `session_key`, `session_value`)VALUES(:phpsessid, :session_key, :session_value)
SQL;
        $stmt = $connection->prepare($query);
        $stmt->bindParam(':phpsessid', $this->sessionId, \PDO::PARAM_STR);
        $stmt->bindParam(':session_key', $key, \PDO::PARAM_STR);
        $stmt->bindParam(':session_value', $value, \PDO::PARAM_STR);
        $stmt->execute();
    }

    public function addUserId($userId){
        $connection = $this->pdo->getConnection();

        $query = <<<SQL
INSERT INTO `sessions` (`phpsessid`, `session_key`, `session_value`)VALUES(:phpsessid, :session_key, :session_value)
SQL;
        $stmt = $connection->prepare($query);
        $stmt->bindParam(':phpsessid', $this->sessionId, \PDO::PARAM_STR);
        $stmt->bindValue(':session_key', 'userId', \PDO::PARAM_STR);
        $stmt->bindParam(':session_value', $userId, \PDO::PARAM_STR);
        $stmt->execute();
    }

    public function getUserIdBySessionId($sessionId){
        $connection = $this->pdo->getConnection();

        $query = <<<SQL
SELECT `session_value` FROM `sessions` WHERE phpsessid=:phpsessid AND session_key=:session_key
SQL;
        $stmt = $connection->prepare($query);
        $stmt->bindParam(':phpsessid', $sessionId, \PDO::PARAM_STR);
        $stmt->bindValue(':session_key', 'userId', \PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC)['session_value'];
    }

    public function getCsrfBySessionId($sessionId){
        $connection = $this->pdo->getConnection();

        $query = <<<SQL
SELECT `session_value` FROM `sessions` WHERE phpsessid=:phpsessid AND session_key=:session_key
SQL;
        $stmt = $connection->prepare($query);
        $stmt->bindParam(':phpsessid', $sessionId, \PDO::PARAM_STR);
        $stmt->bindValue(':session_key', 'csrf', \PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC)['session_value'];
    }
}