<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 12-Jul-18
 * Time: 14:51
 */

namespace Api\Models;

use Phalcon\Mvc\Model;

class Payment extends Model
{
    public $id;

    public $method;

    public function beforeUpdate()
    {
        $di = $this->getDI();
        $redis = $di->get('redis');
        $redis->delete(":GET:payment:");
    }

    public function beforeCreate()
    {
        $di = $this->getDI();
        $redis = $di->get('redis');
        $redis->delete(":GET:payment:");
    }

    public function beforeDelete()
    {
        $di = $this->getDI();
        $redis = $di->get('redis');
        $redis->delete(":GET:payment:");
    }
}