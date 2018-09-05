<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 12-Jul-18
 * Time: 14:52
 */

namespace Api\Controllers;
use Api\Models\Payment;

class PaymentController extends BaseController
{
    public function __construct()
    {
        $this->model = new Payment();
        //parent::__construct();
    }
}