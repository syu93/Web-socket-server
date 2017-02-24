<?php

$loader = require __DIR__ . '/../vendor/autoload.php';
$loader->add('Pinnackl', realpath(__DIR__.'/..').'/src');

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;

// Load Pinnackl Web Socket Server
use Pinnackl\Wss;

$server = IoServer::factory(
        new HttpServer(
            new WsServer(
                new Wss()
            )
        ),
        9000
    );

$server->run();
