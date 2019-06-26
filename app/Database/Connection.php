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
                
                self::$connection = mysqli_connect($host, $user, $pass, $database, $port, null);

                if ($connection->connect_errno) {
                    throw new DatabaseException("Could not connect to the database with the currenct settings. ERRNO: $connection->connect_error");
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