<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 10-Jul-18
 * Time: 16:23
 */
namespace Api\Controllers;
use Api\Models\Booking;
use Exception;
use Phalcon\Http\Response;

class BookingController extends BaseController
{
    public function __construct()
    {
        $this->model = new Booking();
        //parent::__construct();
    }

    function getByClientId($id)
    {
        $bookings = Booking::find([
            'conditions' => 'id_user = ?1',
            'bind'       => [1 => $id,]
        ]);

        if(empty($bookings)){
            throw new Exception('could not get by client id.', 409);
        }
        else
            return json_encode($bookings);
    }
    function post()
    {
        $decoded = $this->requestBody;

        $price = $decoded['price'];
        if($price == null || (int)$price == 0){
            $response = new Response();
            $response->setJsonContent(
                [
                    'status' => 'price',
                ]
            );
            return $response;
        }

        /* email */
        $email = $decoded['email'];
        $domain = ltrim(stristr($email, '@'), '@');
        $user   = stristr($email, '@', TRUE);
        $dns = ['yahoo.com', 'gmail.com'];
        if(!preg_match('/[a-zA-Z0-9]*/', $user) || empty($user) || empty($domain) || !in_array($domain, $dns))
        {
            $response = new Response();
            $response->setJsonContent(
                [
                    'status' => 'email',
                ]
            );
            return $response;
        }

        /* phone */
        $phone = $decoded['phone_number'];
        if(!preg_match("/07[0-9]{8}/", $phone) || strlen($phone) > 10) {
            $response = new Response();
            $response->setJsonContent(
                [
                    'status' => 'phone',
                ]
            );
            return $response;
        }

        $item = $this->model;
        foreach ($decoded as $key => $value) {
            $item->$key = $value;
        }
        $status = $item->save();

        if($status)
        {
            return response(200,"ok",NULL);
        }
        else
        {
            throw new Exception('could not post.', 409);
        }
    }

    function put($id)
    {
        $decoded = $this->requestBody;

        $item = $this->model;
        $item = $item::findFirst(['conditions' => 'id = :id:', 'bind' => ['id' => $id]]);
        if(!$item){
            throw new Exception('id not found.', 409);
        }

        foreach ($decoded as $key => $value) {
            $item->$key = $value;
        }
        $status = $item->save();

        if($status)
        {
            return response(200,"ok",NULL);
        }
        else
        {
            throw new Exception('could not post.', 409);
        }
    }
}