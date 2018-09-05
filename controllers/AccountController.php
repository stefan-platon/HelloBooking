<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 10-Jul-18
 * Time: 12:26
 */
namespace Api\Controllers;
use Api\Models\Account;

class AccountController extends BaseController
{
    public function __construct()
    {
        $this->model = new Account();
        //parent::__construct();
    }
}