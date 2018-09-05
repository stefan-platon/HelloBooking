<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 19-Jul-18
 * Time: 13:02
 */
namespace Api\Controllers;
use \Phalcon\Di\Injectable;
use Exception;
require_once 'response.php';

class BaseController extends Injectable
{
    protected $model = null;
    protected $conditions = '';
    protected $bind = [];

    public function __construct()
    {

    }

    public function prepareWhereCondition()
    {
        $q = $this->request->get('q');
        if(isset($q)) {
            $q = json_decode($q, true);
            foreach ($q as $par) {
                if(!isset($par['key']))
                    throw new Exception('missing key in where condition.', 409);
                else if(!isset($par['op']))
                    throw new Exception('missing operator in where condition.', 409);
                else if(!isset($par['value']))
                    throw new Exception('missing value in where condition.', 409);
                $this->conditions .= $par['key'] . ' ' . $par['op'] . ' :value' . $par['key'] . ': ';
                $this->bind = ['value' . $par['key'] => $par['value']];
            }
        }
    }

    function get()
    {
        $this->prepareWhereCondition();

//        $table = $this->model->getSource();
//        $user_redis = $this->redis->get(":GET:$table:");
//        if(!$user_redis)
//        {
//            $items = $this->model->findFirst(['conditions' => $this->conditions, 'bind' => $this->bind]);
//            $this->redis->save(":GET:$table:", $items);
//        }
//        else
//        {
//            $items = $user_redis;
//        }

        $items = $this->model->find(['conditions' => $this->conditions, 'bind' => $this->bind]);

        if(empty($items)){
            throw new Exception('could not get.', 409);
        }
        else
            return json_encode($items);
    }

    function getById($id)
    {
        $items = $this->model->findFirst([
            'conditions' => 'id = ?1',
            'bind'       => [1 => $id,]
        ]);

        if(empty($items)){
            throw new Exception('could not get by id.', 409);
        }
        else
            return json_encode($items);
    }

    function post()
    {
        $decoded = $this->requestBody;

        $item = $this->model;
        foreach ($decoded as $key => $value) {
            $item->$key = $value;
        }
        $status = $item->save();

        if($status)
        {
            return response(200,"ok",NULL);
        }
        else
        {
            throw new Exception('could not post.', 409);
        }
    }

    function put($id)
    {
        $decoded = $this->requestBody;

        $item = $this->model;
        $item = $item::findFirst(['conditions' => 'id = :id:', 'bind' => ['id' => $id]]);
        if(!$item){
            throw new Exception('id not found.', 409);
        }

        foreach ($decoded as $key => $value) {
            $item->$key = $value;
        }
        $status = $item->save();

        if($status)
        {
            return response(200,"ok",NULL);
        }
        else
        {
            throw new Exception('could not post.', 409);
        }
    }

    function delete($id)
    {
        $item = $this->model->findFirst(['conditions' => 'id = :id:', 'bind' => ['id' => $id]]);
        if(!$item){
            throw new Exception('id not found.', 409);
        }
        $status = $item->delete();

        if($status)
        {
            return response(200,"ok",NULL);
        }
        else
        {
            throw new Exception('could not delete.', 409);
        }
    }
}