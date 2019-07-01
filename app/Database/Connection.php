<?php
    namespace Invest\Database;

    use Invest\Exceptions;

    class Connection {
        private $settings;
        private static $connection;

        private function __construct() {}

        /**
         * This function gets a new connection with the database. 
         * @return A connection with the database.
         */
        public function get() {
            if (!isset($connection)) {
                $this->settings = parse_ini_file("config.ini");

                $host = $this->settings["host"];
                $port = $this->settings["port"];
                $user = $this->settings["username"];
                $pass = $this->settings["password"];
                $database = $this->settings["database"];
                
                try {
                    self::$connection = new PDO("mysql:host=$host;port=$port;dbname=$database", $user, $pass, array(
                        PDO::ATTR_PERSISTENT => true
                    ));
                } catch (PDOException $e) { 
                    throw new DatabaseException("Could not connect to the database with the currenct settings. PDOException: $e->getMessage()");
                }
            }

            return self::$connection;
        }

        public function close() {
            if (!isset($connection)) {
                $this->connection->close();
            }
        }
    }