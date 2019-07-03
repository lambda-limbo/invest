<?php declare(strict_types=1);

    namespace Invest\Middleware;

    final class Session {
        private function __construct() {}

        public static function get(string $key, $default = null) {
            if (self::exists($key)) {
                return $_SESSION[$key];
            }

            return $default;
        }

        public static function exists(string $key) : bool {
            return isset($_SESSION[$key]);
        }

        public static function create(string $key, $value) {
            return $_SESSION[$key] = $value;
        }

        public static function destroy(string $key) : void {
            if (self::exists($key)) {
                unset($key);
            }
        }

        public static function clear() : void {
            session_unset();
            session_destroy();
        }
    }