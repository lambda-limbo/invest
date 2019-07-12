<?php declare(strict_types=1);

namespace Invest\Middleware;

use Invest\Database\Connection;
use Invest\Database\Query;
use Invest\Exceptions\DatabaseException;

/**
* Middleware to authenticate in an user in the system. The only function within
* this class is an authentication that returns a boolean to whether or not a user
* can enter the system.
*/
final class Authentication {
    private function __construct() {
        // does nothing    
    }
    
    public static function authenticate(string $user, string $password) : bool {
        if (strlen($user) != 0 && strlen($password) != 0) {

            $connection = Connection::get();

            $login = $_POST["username"];
            $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

            $q = new Query("CALL P_VERIFY_LOGIN(:USER, :PASS)");
            $q->execute(array(':USER' => $login, ':PASS' => $password));
            
            $result = $q->fetch();
            if (count($result) === 0) { 
                return false; 
            }

            $username = $result['USER_LOGIN'];
            $wallet = $result['USER_WALLET'];
            $code = $result['USER_PK'];

            if ($result['USER_ADM'] == 0) {
                Session::create("USER", array('username' => $username, 'wallet' => $wallet, 'code' => $code, 'adm' => 0));
            } else if ($result['USER_ADM'] == 1 ) {
                Session::create("ADMIN", array('username' => $username, 'wallet' => $wallet, 'code' => $code, 'adm' => 1));
            }

            return true;
            
        }

        return false;    
    }
}