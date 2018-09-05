<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 16-Jul-18
 * Time: 10:44
 */

namespace Api\Controllers;

require('vendor/autoload.php');
use Api\Models\Driverstatus;
use WebSocket\BadOpcodeException;
use WebSocket\Client;
use Exception;

class DriverstatusController extends BaseController
{
    public function __construct()
    {
        $this->model = new Driverstatus();
        //parent::__construct();
    }

    function put($id)
    {
        $decoded = $this->requestBody;

        $driver = new Driverstatus();
        $driver = $driver::findFirst(['conditions' => 'id = :id:', 'bind' => ['id' => $id]]);
        if(!$driver){
            throw new Exception('id not found.', 409);
        }

        foreach ($decoded as $key => $value) {
            $driver->$key = $value;
        }
        $status = $driver->save();
        if(!$status){
            throw new Exception('could not put.', 409);
        }

        $data[] = [
            'id_user'   => $id,
            'online' => $decoded['online'],
            'status'   => $decoded['status'],
            'job' => $decoded['job'],
            'lat' => $decoded['lat'],
            'lng' => $decoded['lng']
        ];

        /* socket */
        $client = new Client("ws://localhost:8686");
        try {
            $client->send(json_encode($data));
        } catch (BadOpcodeException $e) {
            throw new Exception('could not send to socket.', 409);
        }

        return 'ok';
    }

    function getLogged()
    {
        $driver = new Driverstatus();
        $items = $driver::find([
            'conditions' => 'online = 1'
        ]);

        if(empty($items)){
            throw new Exception('could not logged.', 409);
        }
        else
            return json_encode($items);
    }
}