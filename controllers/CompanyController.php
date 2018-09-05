<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 10-Jul-18
 * Time: 12:54
 */
namespace Api\Controllers;
use Api\Models\Company;

class CompanyController extends BaseController
{
    public function __construct()
    {
        $this->model = new Company();
        //parent::__construct();
    }
}