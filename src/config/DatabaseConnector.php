<?php

    namespace app\config;

    class DatabaseConnector{
        private $host = '127.0.0.1';
        private $username = 'root';
        private $password = '123';
        private $db = 'order_system';
        private $port = 3306;

        private $connectionString = null;

        function __construct(){

            try {
                $this->connectionString = new \PDO(
                    "mysql:host=$this->host;port=$this->port;charset=utf8mb4;dbname=$this->db",
                    $this->username, $this->password
                );

            } catch (\PDOException $e) {
                exit('Error running from pm'.$e->getMessage());
            }
            
        }

        public static function getConnection(){
            return (new self)->connectionString;
        }

    }
?>