<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 17-Jul-18
 * Time: 12:58
 */
require __DIR__ . '/vendor/autoload.php';
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
require 'Socket.php';

$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new Socket()
        )
    ),
    8686,
    "127.0.0.1"
);

$server->run();