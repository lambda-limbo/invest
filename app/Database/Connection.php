<?php
    namespace Invest\Database;

    use Invest\Exceptions;

    class Connection {
        private $settings;
        private static $connection;

        public function __construct() {
            $settings = parse_ini_file("config.ini");
        }

        /**
         * @brief This function gets a new connection with the database 
         * @returns A connection with the database.
         */
        public function get() {
            if (!isset($connection)) {
                $host = $settings["host"];
                $port = $settings["port"];
                $user = $settings["username"];
                $pass = $settings["password"];
                $database = $settings["database"];
                
                try {
                    self::$connection = new PDO("mysql:host=$host;port=$port;dbname=$database", $user, $pass, array(
                        PDO::ATTR_PERSISTENT => true
                    ));
                } catch (PDOException $e) { 
                    throw new DatabaseException("Could not connect to the database with the currenct settings. PDOException: $e->getMessage()");
                }
            }

            return $connection;
        }

        public function close() {
            if (!isset($connection)) {
                $connection->close();
            }
        }
    }