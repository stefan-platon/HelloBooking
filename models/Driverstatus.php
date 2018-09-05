<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 16-Jul-18
 * Time: 09:27
 */

namespace Api\Models;

use Phalcon\Mvc\Model;

class Driverstatus extends Model
{
    public $id;

    public $online;

    public $status;

    public $lat;

    public $lng;

    public function beforeUpdate()
    {
        $di = $this->getDI();
        $redis = $di->get('redis');
        $redis->delete(":GET:driverstatus:");
    }

    public function beforeCreate()
    {
        $di = $this->getDI();
        $redis = $di->get('redis');
        $redis->delete(":GET:driverstatus:");
    }

    public function beforeDelete()
    {
        $di = $this->getDI();
        $redis = $di->get('redis');
        $redis->delete(":GET:driverstatus:");
    }
}