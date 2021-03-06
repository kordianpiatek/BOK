<?php
class Database
{
    private static $instance;
    private $conn;
    private $host = 'localhost';
    private $user = 'root';
    private $pass = 'coderslab';
    private $dbName = 'bok';
    private function __construct()
    {
        try {
            $this->conn = new PDO("mysql:host=$this->host;dbname=$this->dbName;charset=utf8", $this->user, $this->pass);
        } catch (PDOException $ex) {
            die("Błąd połączenia: " . $ex->getMessage());
        }
    }
    private function __clone(){}
    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance->getConnection();
    }
    public function getConnection()
    {
        return $this->conn;
    }
}