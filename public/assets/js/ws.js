'use strict'

let socket = new WebSocket('wss://localhost:8008');

socket.onopen = (event) => {
    socket.send('Hello from here!');
}