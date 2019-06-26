<?php
    namespace Invest\Exceptions;

    class DatabaseException extends Exception {
        /**
         * @brief Creates a new exception for throwing it wherever you want.
         * @param message The message of the exception
         * @param code The exception code
         * 
         */
        public function __construct($message, $code, Exception $previous = null) {
            parent::__construct($message, $code, $previous);
        }

        public function __toString() {
            return "<".__CLASS__.">" . " $this->$message";
        }
    }