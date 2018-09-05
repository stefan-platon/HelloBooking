<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 16-Jul-18
 * Time: 09:27
 */

namespace Api\Models;

use Phalcon\Mvc\Model;

class Voucher extends Model
{
    public $id;

    public $code;

    public $value;

    public $type;

    public $from_date;

    public $to_date;

    public $enabled;

    public function beforeUpdate()
    {
        $di = $this->getDI();
        $redis = $di->get('redis');
        $redis->delete(":GET:voucher:");
    }

    public function beforeCreate()
    {
        $di = $this->getDI();
        $redis = $di->get('redis');
        $redis->delete(":GET:voucher:");
    }

    public function beforeDelete()
    {
        $di = $this->getDI();
        $redis = $di->get('redis');
        $redis->delete(":GET:voucher:");
    }
}