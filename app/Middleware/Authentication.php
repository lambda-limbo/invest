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

                $userlogin = $_POST["username"];
                $userpass = $_POST["password"];
                $query = "CALL P_VERIFY_LOGIN('$userlogin', '$userpass')";
                $statement = $connection->query($query);
                $result = $statement->rowCount();
                $reg = $statement->fetch(PDO::FETCH_ASSOC);
                $username = $reg['USER_LOGIN'];
                $wallet = $reg['USER_WALLET'];
                //print $_SESSION['USER']['username'];
                //print $_SESSION['USER']['wallet'];
                if ($result == 1){
                    Session::create("USER", array('username' => $username, 'wallet' => $wallet ));
                 return true;
                }
                Connection::close();

            return false;
        }
    }