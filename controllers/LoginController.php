<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 12-Jul-18
 * Time: 13:50
 */

namespace Api\Controllers;

use Api\Models\Driverstatus;
use Phalcon\Http\Response;
use Phalcon\Mvc\Controller;
use Api\Models\User;
use Exception;

class LoginController extends Controller
{
    function post()
    {
        $decoded = $this->requestBody;

        $email = $decoded['email'];
        $password = $decoded['password'];

        // Create a response
        $response = new Response();

        /* email */
        $domain = ltrim(stristr($email, '@'), '@');
        $user   = stristr($email, '@', TRUE);
        $dns = ['yahoo.com', 'gmail.com'];
        if(!preg_match('/[a-zA-Z0-9]*/', $user) || empty($user) || empty($domain) || !in_array($domain, $dns))
        {
            $response->setJsonContent(
                [
                    'status' => 'email',
                ]
            );
            return $response;
        }

        /* find user by name, email, phone */
        $user = User::findFirst([
            'conditions' => "email = :email: and password = :password:",
            'bind'       => ['email' => $email, 'password' => $password]
        ]);
        if($user == null){
            $response->setJsonContent(
                [
                    'status' => 'failed',
                ]
            );
            return $response;
        }
        $id_user = $user->id;
        $user_type = $user->user_type;

        /* driver -> online */
//        if($user_type == 'driver'){
//            $driver = Driverstatus::findFirst([
//                'conditions' => "id = :id:",
//                'bind'       => ['id' => $id_user]
//            ]);
//            $driver->online = '1';
//            $driver->status = 'away';
//            $status = $driver->save();
//
//            /* if error */
//            if(!$status){
//                $response->setJsonContent(
//                    [
//                        'status' => 'driver',
//                    ]
//                );
//                return $response;
//            }
//        }

        $response->setJsonContent(
            [
                'status' => 'success',
                'token' => substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, 20),
                'id_user' => $id_user
            ]
        );
        return $response;
    }
}