<?php declare(strict_types=1);

namespace Invest\Middleware;

use Invest\Database\Query;

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

            $login = $_POST["username"];
            $password = $_POST["password"];
            
            $q = new Query("SELECT * FROM TB_USER WHERE USER_LOGIN = :USER");
            $q->execute(array(':USER' => $login));
            
            $result = $q->fetch();

            if (!$result) { 
                return false; 
            }

            if (!password_verify($password, $result['USER_PASSWORD'])) {
                return false;
            }

            $username = $result['USER_LOGIN'];
            $wallet = $result['USER_WALLET'];
            $code = $result['USER_PK'];
            $name = $result['USER_NAME'];

            if ($result['USER_ADM'] == 0) {
                Session::create("USER", array('username' => $username, 'wallet' => $wallet, 'code' => $code, 'name' => $name));
            } else if ($result['USER_ADM'] == 1) {
                Session::create("ADMIN", array('username' => $username, 'wallet' => $wallet, 'code' => $code,'name' =>$name));
            }

            return true;
        }

        return false;    
    }
}