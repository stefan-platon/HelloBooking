<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 12-Jul-18
 * Time: 15:08
 */

namespace Api\Controllers;

use Api\Models\Cars;

class CarsController extends BaseController
{
    public function __construct()
    {
        $this->model = new Cars();
        //parent::__construct();
    }
}