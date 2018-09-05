<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 18-Jul-18
 * Time: 14:53
 */
namespace Api\Plugins;
use Phalcon\Mvc\User\Plugin;
use Api\Models\User;
use WebSocket\Exception;

class SecurityPlugin extends Plugin
{
    public function isOK()
    {
        if($_SERVER['REQUEST_URI'] == '/api/user' && $_SERVER['REQUEST_METHOD'] === 'POST'){
            return true;
        }

        $headers = $this->request->getHeaders();
        $email = $headers['Php-Auth-User'];
        $password = $headers['Php-Auth-Pw'];

        $user_redis = $this->redis->get(":AUTH:$email:$password");
        if(!$user_redis)
        {
            $user = User::findFirst([
                'conditions' => "email = :email: and password = :password:",
                'bind'       => ['email' => $email, 'password' => $password]
            ]);
            $this->redis->save(":AUTH:$email:$password", $user);
        }
        else
        {
            $user = $user_redis;
        }

        if($user)
        {
            if($user->password != $password)
            {
                throw new Exception('Bad password.', 409);
            }
            /*if($user->id_company != $id_company)
            {
                throw new Exception('Company missing.', 409);

            }*/
            return true;
        }
        throw new Exception('Email was not found.', 409);
    }
}