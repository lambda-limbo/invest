<?php declare(strict_types=1);

    namespace Invest\Websockets;

    use Ratchet\MessageComponentInterface;
    use Ratchet\ConnectionInterface;

    class Sockets implements MessageComponentInterface {
        protected $clients;

        public function __construct() {
            $this->clients = new \SplObjectStorage;
        }

        public function onOpen(ConnectionInterface $conn) {
            $this->clients->attach($conn);
        }

        public function onMessage(ConnectionInterface $from, $msg) {
            echo $msg;
        }

        public function onClose(ConnectionInterface $conn) {

        }

        public function onError(ConnectionInterface $conn, \Exception $e) {
            echo $e->getMessage();
        }
    }