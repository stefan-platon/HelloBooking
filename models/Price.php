<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 10-Jul-18
 * Time: 16:10
 */

namespace Api\Models;
use Phalcon\Mvc\Model;

class Price extends Model
{
    public $id;

    public $id_company;

    public $id_cartype;

    public $id_user;

    public $distance_threshold;

    public $charge;

    public $type;

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
        $redis->delete(":GET:price:");
    }

    public function beforeCreate()
    {
        $di = $this->getDI();
        $redis = $di->get('redis');
        $redis->delete(":GET:price:");
    }

    public function beforeDelete()
    {
        $di = $this->getDI();
        $redis = $di->get('redis');
        $redis->delete(":GET:price:");
    }
}