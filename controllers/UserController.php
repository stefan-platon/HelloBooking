<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 10-Jul-18
 * Time: 16:26
 */

namespace Api\Controllers;
use Api\Models\Driverstatus;
use Api\Models\User;
use Exception;
use Phalcon\Http\Response;

class UserController extends BaseController
{
    public function __construct()
    {
        $this->model = new User();
        //parent::__construct();
    }

    function getByName($name)
    {
        $user = new User();
        $user = $user::findFirst([
            'conditions' => 'name = :nume:',
            'bind'       => ['nume' => $name]
        ]);

        if(empty($user)){
            throw new Exception('could not get by name.', 409);
        }
        else
            return json_encode($user);
    }

    function put($id)
    {
        $decoded = $this->requestBody;

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

        $user = new User();
        $user = $user::findFirst(['conditions' => 'id = :id:', 'bind' => ['id' => $id]]);
        if(!$user){
            throw new Exception('id not found.', 409);
        }

        foreach ($decoded as $key => $value) {
            $user->$key = $value;
        }
        $status = $user->save();

        if($status)
        {
            return 'ok';
        }
        else
        {
            throw new Exception('could not put.', 409);
        }
    }

    function post()
    {
        $decoded = $this->requestBody;

        $email = $decoded['email'];
        /* email */
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

        $user = new User();
        foreach ($decoded as $key => $value) {
            $user->$key = $value;
        }
        $status1 = $user->save();

        /* driverstatus */
        $status2 = true;
//        if($user->user_type == 'driver'){
//            $driver = new Driverstatus();
//            $driver->id = $user->id;
//            $driver->online = '0';
//            $status2 = $driver->save();
//        }

        if($status1 && $status2)
        {
            return 'ok';
        }
        else
        {
            throw new Exception('could not post.', 409);
        }
    }

    function delete($id)
    {
        $account = User::findFirst(['conditions' => 'id = :id:', 'bind' => ['id' => $id]]);
        if(!$account){
            throw new Exception('id not found.', 409);
        }

        $status1 = true;
        if($account->user_type == 'driver'){
            $driver = Driverstatus::findFirst(['conditions' => 'id = :id:', 'bind' => ['id' => $id]]);
            if(!$driver){
                throw new Exception('id not found.', 409);
            }
            $status1 = $account->delete();
        }
        $status2 = $account->delete();

        if($status1 && $status2)
        {
            return 'ok';
        }
        else
        {
            throw new Exception('could not delete.', 409);
        }
    }
}