<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 10-Jul-18
 * Time: 13:56
 */
namespace Api\Controllers;
use Api\Models\Cartype;
use Exception;

class CartypeController extends BaseController
{
    public function __construct()
    {
        $this->model = new Cartype();
        //parent::__construct();
    }

    function put($id)
    {
        $decoded = $this->request->getPost();
        $cartype = new Cartype();
        $cartype = $cartype::findFirst(['conditions' => 'id = :id:', 'bind' => ['id' => $id]]);
        if(!$cartype){
            throw new Exception('id not found.', 409);
        }
        foreach ($decoded as $key => $value) {
            $cartype->$key = $value;
        }

        $hasFiles = $this->request->hasFiles();
        if($hasFiles)
        {
            $picture = $this->request->getUploadedFiles();
            $extension = $picture[0]->getExtension();
            $size = $picture[0]->getSize();
            $location = "pictures/" . $picture[0]->getName();

            // Allow certain file formats
            if($extension != "jpg" && $extension != "png" && $extension != "jpeg") {
                throw new Exception('image extension not supported.', 409);
            }
            // Allow a maximum size in bytes (1 Mb)
            if($size > 1000000) {
                throw new Exception('image size too big.', 409);
            }
            // Save image
            if (!($picture[0]->moveTo($location))) {
                throw new Exception('could not save image.', 409);
            }
            // Put into database
            $cartype->picture = $location;
        }else return "no file";

        $status = $cartype->save();

        if($status)
        {
            return 'ok';
        }
        else
        {
            throw new Exception('could not put.', 409);
        }
    }

    function getTypes()
    {
        $cartypes = Cartype::find(['columns' => 'distinct type']);

        if(empty($cartypes)){
            throw new Exception('could not get.', 409);
        }
        else
            return json_encode($cartypes);
    }

    function getTypesByCapacity($capacity)
    {
        $cartypes = Cartype::find(['conditions' => 'capacity >= :cap:',
            'columns' => 'distinct type', 'bind' => ['cap' => $capacity]]);

        if(empty($cartypes)){
            throw new Exception('could not get.', 409);
        }
        else{
            foreach ($cartypes as $cartype) {
                $data[] = [
                    'type' => $cartype->type
                ];
            }
        }
        return json_encode($cartypes);
    }

    function post()
    {
        $decoded = $this->request->getPost();
        $cartype = new Cartype();
        foreach ($decoded as $key => $value) {
            $cartype->$key = $value;
        }

        $hasFiles = $this->request->hasFiles();
        if($hasFiles)
        {
            $picture = $this->request->getUploadedFiles();
            $extension = $picture[0]->getExtension();
            $size = $picture[0]->getSize();
            $location = "pictures/" . $picture[0]->getName();

            // Allow certain file formats
            if($extension != "jpg" && $extension != "png" && $extension != "jpeg") {
                throw new Exception('image extension not supported.', 409);
            }
            // Allow a maximum size in bytes (1 Mb)
            if($size > 1000000) {
                throw new Exception('image size too big.', 409);
            }
            // Save image
            if (!($picture[0]->moveTo($location))) {
                throw new Exception('could not save image.', 409);
            }
            // Put into database
            $cartype->picture = $location;
        }

        $status = $cartype->save();

        if($status)
        {
            return 'ok';
        }
        else
        {
            throw new Exception('could not post.', 409);
        }
    }
}