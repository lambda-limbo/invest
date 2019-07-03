<?php
    namespace Invest\Database;

    use \PDO;

    use Invest\Exceptions;
    

    class Connection {
        private static $settings;
        private static $connection;

        private function __construct() {}

        /**
         * Gets a new connection with the database or throw an exception in case of error. 
         * @return A connection with the database.
         */
        public static function get() {
            if (!isset($connection)) {
                self::$settings = parse_ini_file("config.ini");

                $host = self::$settings["host"];
                $port = self::$settings["port"];
                $user = self::$settings["username"];
                $pass = self::$settings["password"];
                $database = self::$settings["database"];
                
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

<<<<<<< HEAD
        public function close() {
=======
        public static function close() {
>>>>>>> rafael
            if (!isset(self::$connection)) {
                self::$connection->close();
            }
        }
    }