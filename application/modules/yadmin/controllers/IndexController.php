<?php
namespace application\modules\yadmin\controllers;

use application\base\AdminBaseController;

class IndexController extends AdminBaseController
{

    public function IndexAction()
    {
        $this->view->menuflag = 'workbench';
    }

}
