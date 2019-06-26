<?php declare(strict_types=1);

    namespace Invest\Middleware;

    use Invest\Database\Connection;

    /**
     * @brief Middleware to authenticate in and out an user in the system.
     */
    final class Authentication {
        private function __construct() {}

        public static function authenticate(string $user, string $password) : bool {
            if (strlen($user) == 0 || strlen($password) == 0) {
                return false;
            }

            
        }
    }