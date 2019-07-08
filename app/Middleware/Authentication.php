<?php declare(strict_types=1);

    namespace Invest\Middleware;

    use Invest\Database\Connection;
    use \PDO;
    /**
     * @brief Middleware to authenticate in and out an user in the system.
     */
    final class Authentication {
        private function __construct() {}

        public static function authenticate(string $user, string $password) : bool {
            if (strlen($user) == 0 || strlen($password) == 0) {
                return false;
            }


                $connection = Connection::get();

                $param1 = $_POST["username"];
                $param2 = $_POST["password"];

                //echo $param1;
                //echo "----------------"; 
                //echo $param2;
                //echo "-------------";
                $query = "CALL P_VERIFY_LOGIN('$param1', $param2)";
                //echo $query;

                $statement = $connection->query($query);
                $result = $statement->fetchColumn();
               
                if ($result == 1){
                    Session::create("USER", array('username' => $user));
                    return true;
                }
                Connection::close();

            return false;
        }
    }