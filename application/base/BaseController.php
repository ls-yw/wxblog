<?php
namespace application\base;

use application\logic\ConfigLogic;
use woodlsy\phalcon\basic\BasicController;

class BaseController extends BasicController
{
    public function initialize()
    {
        parent::initialize();

        $systemConfig = (new ConfigLogic())->getConfig('system');
        $this->view->systemConfig = $systemConfig;
    }

}