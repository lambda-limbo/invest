<?php declare(strict_types=1);

namespace Invest\Middleware;

use Invest\Database\Connection;

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
        if (strlen($user) == 0 || strlen($password) == 0) {
            return false;
        }
        
        
        $valid_username = 1;
        $valid_password = 1;//password_verify($password, );
        
        if ($valid_username && $valid_password) {
            Session::create("USER", array('username' => $user));
            return true;
        }
        
        return false;
    }
}