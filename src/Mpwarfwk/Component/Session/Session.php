<?php

namespace Mpwarfwk\Component\Session;

use Mpwarfwk\Component\Db\PDODatabase;

class Session {

    const RANDOM_CHARS_CSRF = 70;

    private $csrfKey = 'csrf';
    private $csrfValue;
    private $sessionId;

    public function __construct(){
        session_start();
        $this->sessionId = session_id();
        if(!$this->isSessionIdExist($this->sessionId)){
            $this->generateCsrf($this->sessionId);
        }
    }

    public function isSessionIdExist($sessionId){
        $pdo = new PDODatabase('localhost', 'seguretat', 'root', 'root');
        $connection = $pdo->getConnection();

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

        $pdo = new PDODatabase('localhost', 'seguretat', 'root', 'root');
        $connection = $pdo->getConnection();

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
        session_destroy();
    }

    public function addKeyValue($key, $value){
        $pdo = new PDODatabase('localhost', 'seguretat', 'root', 'root');
        $connection = $pdo->getConnection();

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
        $pdo = new PDODatabase('localhost', 'seguretat', 'root', 'root');
        $connection = $pdo->getConnection();

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
        $pdo = new PDODatabase('localhost', 'seguretat', 'root', 'root');
        $connection = $pdo->getConnection();

        $query = <<<SQL
SELECT `session_value` FROM `sessions` WHERE phpsessid=:phpsessid AND session_key=:session_key
SQL;
        $stmt = $connection->prepare($query);
        $stmt->bindParam(':phpsessid', $sessionId, \PDO::PARAM_STR);
        $stmt->bindValue(':session_key', 'userId', \PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC)['session_value'];
    }
}