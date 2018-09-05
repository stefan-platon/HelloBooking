<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 12-Jul-18
 * Time: 15:08
 */

namespace Api\Models;

use Phalcon\Mvc\Model;

class Cars extends Model
{
    public $id;

    public $type;

    public function beforeUpdate()
    {
        $di = $this->getDI();
        $redis = $di->get('redis');
        $redis->delete(":GET:cars:");
    }

    public function beforeCreate()
    {
        $di = $this->getDI();
        $redis = $di->get('redis');
        $redis->delete(":GET:cars:");
    }

    public function beforeDelete()
    {
        $di = $this->getDI();
        $redis = $di->get('redis');
        $redis->delete(":GET:cars:");
    }
}