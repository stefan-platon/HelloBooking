<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 23-Jul-18
 * Time: 13:00
 */
namespace Api\Controllers;
use Phalcon\Cache\Backend\Redis;
use Phalcon\Cache\Frontend\Data as FrontData;

class RedisController extends BaseController
{
    function get()
    {
        // Cache data for 2 days
        $frontCache = new FrontData(
            [
                "lifetime" => 172800,
            ]
        );

        // Create the Cache setting redis connection options
        $cache = new Redis(
            $frontCache,
            [
                "host"       => "localhost",
                "port"       => 6379,
                "auth"       => "",
                "persistent" => false,
                "index"      => 0,
            ]
        );

        // Cache arbitrary data
        $cache->save("my-data", [1, 2, 3, 4, 5]);

        // Get data
        $data = $cache->get("my-data");

        var_dump($data);
    }
}