<?php
    require_once '../config/env.php';

    class Database {

        private $hostname = hostname;
        private $database = database;
        private $username = username;
        private $password = password;
        private $charset = 'utf8';

        function connection() {
            try {
                $connection = 'mysql:host=' . $this->hostname . ';dbname=' . $this->database . ';charset=' . $this->charset;
                $options = [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ];
                $pdo = new PDO($connection, $this->username, $this->password, $options);
                return $pdo;
            } catch (PDOException $e) {
                echo 'Error connection: ' . $e->getMessage();
                exit;
            }
        }
    }

?>