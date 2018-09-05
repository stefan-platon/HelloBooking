<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 10-Jul-18
 * Time: 16:24
 */
namespace Api\Controllers;
use Api\Models\Price;

class PriceController extends BaseController
{
    public function __construct()
    {
        $this->model = new Price();
        //parent::__construct();
    }
}