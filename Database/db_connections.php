<?php
class Database {
    private $host = 'localhost';
    private $dbName = 'batstateu_career_hub';
    private $username = 'root';
    private $password = '';
    private $conn;

    public function getConnect() {
        $this->conn = null;

        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->dbName, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "getConnection error: " . $e->getMessage();
        }

        return $this->conn;
    }
}
?>