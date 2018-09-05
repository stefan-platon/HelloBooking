<?php

use Phalcon\Mvc\Micro;
use Phalcon\Loader;
use Phalcon\Di\FactoryDefault;
use Api\Plugins\SecurityPlugin;
use Phalcon\Db\Adapter\Pdo\Mysql as PdoMysql;
use Phalcon\Cache\Backend\Redis;
use Phalcon\Cache\Frontend\Data as FrontData;
use Api\Plugins\CORSPlugin;

header('Content-Type: application/json; charset=utf-8', true);
header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS, HEAD', true);
header('Access-Control-Allow-Origin: *', true);
header('Access-Control-Allow-Credentials: true', true);
header('Access-Control-Allow-Headers: origin, x-requested-with, content-type, Authorization', true);
header('Allow: GET, POST, HEAD, OPTIONS');
header('Access-Control-Max-Age: 3600', true);

#WHEN METHOD IS OPTIONS
if($_SERVER['REQUEST_METHOD'] == 'OPTIONS'){
    header('Access-Control-Allow-Origin: *', true);
    die;
}

try
{
    // Use Loader() to autoload our model
    $loader = new Loader();

    $loader->registerNamespaces(
        [
            'Api\Models' => __DIR__ . '/models/',
            'Api\Controllers' => __DIR__ . '/controllers/',
            'Swagger\Client' => __DIR__ . '/SwaggerClient-php/lib/',
            'Graphhopper' => __DIR__ . '/SwaggerClient-php/lib/graphhopper/',
            'Api\Plugins' => __DIR__ . '/plugins'
        ]
    );

    $loader->register();

    $di = new FactoryDefault();

    // Set up the database service
    $di->set(
        'db',
        function () {
            return new PdoMysql(
                [
                    'host'     => 'localhost',
                    'username' => 'root',
                    'password' => 'fanica09071997',
                    'dbname'   => 'booker',
                ]
            );
        }
    );

    $di->setShared('redis', function()
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
        return $cache;
    });

    $di->setShared('requestBody', function() {
        $in = file_get_contents('php://input');
        $in = json_decode($in, TRUE);
        if($in === null){
            throw new Exception(
                'There was a problem understanding the data sent to the server by the application.',
                409
            );
        }
        return $in;
    });

    $di->set('dispatcher', function () use ($di) {
        //Obtain the standard eventsManager from the DI
        $eventsManager = $di->getShared('eventsManager');
        //Instantiate the Restful Methods plugin
        $pluginRestfulMethods = new CORSPlugin();
        //Listen for events produced in the dispatcher using the Restful Methods plugin
        $eventsManager->attach('dispatch', $pluginRestfulMethods);
        // Create an instance of the dispatcher.
        $dispatcher = new Phalcon\Mvc\Dispatcher();
        //Bind the EventsManager to the Dispatcher
        $dispatcher->setEventsManager($eventsManager);
        return $dispatcher;
    });

    $app = new Micro($di);

    $app->before(function () use ($app) {
        //var_dump("hello");
        $sec = new SecurityPlugin();
        if(!$sec->isOK()){
            throw new Exception("Authentication failed. User not found.", 45);
        }
    });

    $app->notFound(function () use ($app) {
        $app->response->setStatusCode(405, "Not Found")->sendHeaders();
        //echo $app->router->getRewriteUri();
        echo 'This is crazy, but the page was not found!';
    });

    require_once "routes.php";

    $app->handle();
}
catch(Exception $e)
{
    echo json_encode($e->getMessage());
}
//phpinfo();
