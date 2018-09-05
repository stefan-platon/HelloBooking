<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 10-Jul-18
 * Time: 16:12
 */

namespace Api\Models;
use Phalcon\Mvc\Model;

class Booking extends Model
{
    public $id;

    public $id_company;

    public $id_user;

    public $id_driver;

    public $cartype;

    public $from_address;

    public $to_address;

    public $from_lat;

    public $from_lng;

    public $to_lat;

    public $to_lng;

    public $distance;

    public $duration;

    public $price;

    public $payment_method;

    public $created_at;

    public $updated_at;

    public $status;

    public $name;

    public $email;

    public $date;

    public $time;

    public $phone_number;

    public function beforeValidationOnUpdate()
    {
        $this->updated_at = date('Y-m-d H:i:s');
        $this->status = "dow";
        $this->id_driver = "1";
    }

    public function beforeValidationOnCreate()
    {
        $this->created_at = date('Y-m-d H:i:s');
        $this->updated_at = date('Y-m-d H:i:s');
        $this->status = "dow";
        $this->id_driver = "1";
    }

    public function beforeUpdate()
    {
        $di = $this->getDI();
        $redis = $di->get('redis');
        $redis->delete(":GET:booking:");
    }

    public function beforeCreate()
    {
        $di = $this->getDI();
        $redis = $di->get('redis');
        $redis->delete(":GET:booking:");
    }

    public function beforeDelete()
    {
        $di = $this->getDI();
        $redis = $di->get('redis');
        $redis->delete(":GET:booking:");
    }
}