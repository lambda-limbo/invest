<?php declare(strict_types=1);

    namespace Invest\Websocket;

    use Ratchet\MessageComponentInterface;
    use Ratchet\ConnectionInterface;

    class Socket implements MessageComponentInterface {
        public function onOpen(ConnetionInterface $conn) {
            
        }

        public function onMessage(ConnectionInterface $from, $msg) {
        }

        public function onClose(ConnectionInterface $conn) {
        }

        public function onError(ConnectionInterface $conn, \Exception $e) {

        }
    }