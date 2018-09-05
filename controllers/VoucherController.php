<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 16-Jul-18
 * Time: 09:30
 */

namespace Api\Controllers;
use Api\Models\Voucher;

class VoucherController extends BaseController
{
    public function __construct()
    {
        $this->model = new Voucher();
        //parent::__construct();
    }
}