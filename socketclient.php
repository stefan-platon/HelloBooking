<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 10-Aug-18
 * Time: 12:58
 */
require('vendor/autoload.php');
use WebSocket\BadOpcodeException;
use WebSocket\Client;

/* socket */
$client = new Client("ws://localhost:8686");
while(true){
    $drNumber = rand(0, 3);
    $drivers = [1, 125, 126, 127, 128, 129, 130, 131, 132, 133, 134, 1325];
    $dataToSend = [];
    for($i = 0; $i <= $drNumber; $i++) {
        $driver = $drivers[array_rand($drivers)];

        $data = [
            'id_user'   => $driver,
            'online' => '1',
            'status'   => 'available',
            'job' => 'pob',
            'lat' => '44.4' . rand(10000, 99999),
            'lng' => '26.0' . rand(10000, 99999)
        ];

        $dataToSend[$i] = $data;
    }
    try {
        $client->send(json_encode($dataToSend));
        sleep(2);
    } catch (BadOpcodeException $e) {
        throw new Exception('could not send to socket.', 409);
    }
}