<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 11-Jul-18
 * Time: 08:54
 */
use Phalcon\Mvc\Micro\Collection as MicroCollection;

// Account handler
$route = new MicroCollection();
$route->setPrefix('/api/account')->setHandler('Api\Controllers\AccountController')->setLazy(true);
$route->get('/', 'get');
$route->get('/search/{id:[0-9]+}', 'getById');
$route->put('/{id:[0-9]+}', 'put');
$route->post('/', 'post');
$route->delete('/{id:[0-9]+}', 'delete');
$app->mount($route);

// Booking handler
$route = new MicroCollection();
$route->setPrefix('/api/booking')->setHandler('Api\Controllers\BookingController')->setLazy(true);
$route->get('/', 'get');
$route->get('/search/{id:[0-9]+}', 'getById');
$route->get('/search/client/{id:[0-9]+}', 'getByClientId');
$route->post('/', 'post');
$route->put('/{id:[0-9]+}', 'put');
$route->delete('/{id:[0-9]+}', 'delete');
$app->mount($route);

// Cartype handler
$route = new MicroCollection();
$route->setPrefix('/api/cartype')->setHandler('Api\Controllers\CartypeController')->setLazy(true);
$route->get('/', 'get');
$route->get('/types', 'getTypes');
$route->get('/capacity/{capacity:[0-9]+}', 'getTypesByCapacity');
$route->get('/search/{id:[0-9]+}', 'getById');
$route->put('/{id:[0-9]+}', 'put');
$route->post('/', 'post');
$route->delete('/{id:[0-9]+}', 'delete');
$app->mount($route);

// Company handler
$route = new MicroCollection();
$route->setPrefix('/api/company')->setHandler('Api\Controllers\CompanyController')->setLazy(true);
$route->get('/', 'get');
$route->get('/search/{id:[0-9]+}', 'getById');
$route->put('/{id:[0-9]+}', 'put');
$route->post('/', 'post');
$route->delete('/{id:[0-9]+}', 'delete');
$app->mount($route);

// Price handler
$route = new MicroCollection();
$route->setPrefix('/api/price')->setHandler('Api\Controllers\PriceController')->setLazy(true);
$route->get('/', 'get');
$route->get('/search/{id:[0-9]+}', 'getById');
$route->put('/{id:[0-9]+}', 'put');
$route->post('/', 'post');
$route->delete('/{id:[0-9]+}', 'delete');
$app->mount($route);

// User handler
$route = new MicroCollection();
$route->setPrefix('/api/user')->setHandler('Api\Controllers\UserController')->setLazy(true);
$route->get('/', 'get');
$route->get('/search/{id:[0-9]+}', 'getById');
//$route->get('/search/{name:[\w ]+}', 'getByName');
$route->put('/{id:[0-9]+}', 'put');
$route->post('/', 'post');
$route->delete('/{id:[0-9]+}', 'delete');
$app->mount($route);

// Payment handler
$route = new MicroCollection();
$route->setPrefix('/api/payment')->setHandler('Api\Controllers\PaymentController')->setLazy(true);
$route->get('/', 'get');
$route->get('/search/{id:[0-9]+}', 'getById');
$route->put('/{id:[0-9]+}', 'put');
$route->post('/', 'post');
$route->delete('/{id:[0-9]+}', 'delete');
$app->mount($route);

// Cars handler
$route = new MicroCollection();
$route->setPrefix('/api/cars')->setHandler('Api\Controllers\CarsController')->setLazy(true);
$route->get('/', 'get');
$route->get('/search/{id:[0-9]+}', 'getById');
$route->put('/{id:[0-9]+}', 'put');
$route->post('/', 'post');
$route->delete('/{id:[0-9]+}', 'delete');
$app->mount($route);

// Form handler
$route = new MicroCollection();
$route->setPrefix('/api/form')->setHandler('Api\Controllers\FormController')->setLazy(true);
$route->post('/', 'post');
$app->mount($route);

// Login handler
$route = new MicroCollection();
$route->setPrefix('/api/login')->setHandler('Api\Controllers\LoginController')->setLazy(true);
$route->post('/', 'post');
$app->mount($route);

// Voucher handler
$route = new MicroCollection();
$route->setPrefix('/api/voucher')->setHandler('Api\Controllers\VoucherController')->setLazy(true);
$route->get('/', 'get');
$route->put('/{code:[0-9A-Z]+}', 'put');
$route->post('/', 'post');
$route->delete('/{code:[0-9A-Z]+}', 'delete');
$app->mount($route);

// Driverstatus handler
$route = new MicroCollection();
$route->setPrefix('/api/driverstatus')->setHandler('Api\Controllers\DriverstatusController')->setLazy(true);
$route->get('/', 'get');
$route->get('/logged', 'getLogged');
$route->get('/search/{id:[0-9]+}', 'getById');
$route->put('/{id:[0-9]+}', 'put');
$route->post('/', 'post');
$route->delete('/{id:[0-9]+}', 'delete');
$app->mount($route);

// Redis handler
$route = new MicroCollection();
$route->setPrefix('/api/redis')->setHandler('Api\Controllers\RedisController')->setLazy(true);
$route->get('/', 'get');
$app->mount($route);
