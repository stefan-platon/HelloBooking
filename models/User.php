<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 10-Jul-18
 * Time: 16:06
 */

namespace Api\Models;
use Phalcon\Mvc\Model;

class User extends Model
{
    public $id;

    public $id_company;

    public $user_type;

    public $role;

    public $type;

    public $password;

    public $id_account;

    public $name;

    public $gender;

    public $country;

    public $address;

    public $email;

    public $phone_number;

    public $created_at;

    public $updated_at;

    public function beforeValidationOnUpdate()
    {
        $this->updated_at = date('Y-m-d H:i:s');
    }

    public function beforeValidationOnCreate()
    {
        $this->created_at = date('Y-m-d H:i:s');
        $this->updated_at = date('Y-m-d H:i:s');
    }

    public function beforeUpdate()
    {
        $di = $this->getDI();
        $redis = $di->get('redis');
        $redis->delete(":AUTH:$this->email:$this->password");
        $redis->delete(":GET:user:");
    }

    public function beforeCreate()
    {
        $di = $this->getDI();
        $redis = $di->get('redis');
        $redis->delete(":AUTH:$this->email:$this->password");
        $redis->delete(":GET:user:");
    }

    public function beforeDelete()
    {
        $di = $this->getDI();
        $redis = $di->get('redis');
        $redis->delete(":AUTH:$this->email:$this->password");
        $redis->delete(":GET:user:");
    }
}