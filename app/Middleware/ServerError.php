<?php declare(strict_types=1);

namespace Invest\Middleware;

use Invest\Exceptions\DatabaseException;

class ServerError {
    private function __construct() {}

    public static function get($code, $message, DatabaseException $exception = null) {
        if (isset($exception)) {
            return array('error' => array('CODE' => $code, 'MESSAGE' => $message, 'EXCEPTION' => $exception->getMessage()));
        } 
        return array('error' => array('CODE' => $code, 'MESSAGE' => $message));
    }
}