<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 06-Aug-18
 * Time: 09:22
 */
namespace Api\Plugins;
use Phalcon\Events\Event;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\Micro;
use Phalcon\Mvc\User\Plugin;

class CORSPlugin extends Plugin {

    public function beforeHandleRoute(Event $event, Dispatcher $dispatcher) {
//        $origin = $app->request->getHeader('ORIGIN') ? $app->request->getHeader('ORIGIN') : '*';
//
//        if (strtoupper($app->request->getMethod()) == 'OPTIONS') {
//            $app->response
//                ->setHeader('Access-Control-Allow-Origin', $origin)
//                ->setHeader('Access-Control-Allow-Methods', 'GET,PUT,POST,DELETE,OPTIONS')
//                ->setHeader('Access-Control-Allow-Headers', 'Origin, X-Requested-With, Content-Range, Content-Disposition, Content-Type, Authorization')
//                ->setHeader('Access-Control-Allow-Credentials', 'true');
//
//            $app->response->setStatusCode(200, 'OK')->send();
//
//            exit;
//        }
//
//        $app->response
//            ->setHeader('Access-Control-Allow-Origin', $origin)
//            ->setHeader('Access-Control-Allow-Methods', 'GET,PUT,POST,DELETE,OPTIONS')
//            ->setHeader('Access-Control-Allow-Headers', 'Origin, X-Requested-With, Content-Range, Content-Disposition, Content-Type, Authorization')
//            ->setHeader('Access-Control-Allow-Credentials', 'true');
        $annotations = $this->annotations->getMethod($dispatcher->getActiveController(), $dispatcher->getActiveMethod());
        if ($annotations->has('HTTPMethods')) {
            $annotation = $annotations->get('HTTPMethods');
            if ($this->request->getMethod() == 'OPTIONS') {
                $this->response->setHeader('Allow', implode(',', $annotation->getArguments()));
                $this->response->setJsonContent(array('options' => $annotation->getArguments()));
                $this->response->send();
                // End the request as all we wanted was to send the options.
                return false;
            } elseif (!in_array($this->request->getMethod(), array_change_key_case($annotation->getArguments(), CASE_UPPER))) {
                $this->response->setStatusCode(405, 'Method Not Allowed');
                $this->response->setHeader('Allow', implode(',', $annotation->getArguments()));
                $this->response->send();
                return false;
            }
        }
        return true;
    }
}