<?php declare(strict_types=1);

    namespace Invest\Middleware;

    final class Session {
        private function __construct() {}

        public static function get($key, $default = null) {
            if (self::exists($key)) {
                return $_SESSION[$key];
            }

            return $default;
        }

        public static function exists($key) : bool {
            return isset($_SESSION[$key]);
        }

        public static function destroy($key) : void {
            if (self::exists($key)) {
                unset($key);
            }
        }
    }