<?php declare(strict_types=1);

    namespace Invest\Middleware;

    final class Redirection {
        private function __construct() {}

        public static function to($where) {
            header('Location: /' . $where);
        }

        public static function out() {
            header('Location: /');
        }
    }