<?php

namespace application\modules\index;

use Phalcon\DiInterface;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\ModuleDefinitionInterface;
use Phalcon\Mvc\View;

class Module implements ModuleDefinitionInterface
{
    /**
     * 注册自定义加载器
     */
    public function registerAutoloaders(DiInterface $di = null){}

    /**
     * 注册自定义服务
     */
    public function registerServices(DiInterface $di)
    {
        // Registering a dispatcher
        $di->set(
            "dispatcher",
            function () {
                $dispatcher = new Dispatcher();

                $dispatcher->setDefaultNamespace("application\\modules\\index\\controllers");

                return $dispatcher;
            }
        );
        $di->set(
            "view",
            function () {
                $view = new View();

                $view->setViewsDir("../application/modules/index/views/");

                return $view;
            }
        );
    }
}