<?php
namespace application\base;

use application\logic\ArticleLogic;
use application\logic\ConfigLogic;
use woodlsy\phalcon\basic\BasicController;

class BaseController extends BasicController
{
    protected $page = 1;
    protected $size = 20;

    public function initialize()
    {
        parent::initialize();

        $this->page = (int) $this->get('page', 'int', 1);
        $this->size = (int) $this->get('size', 'int', 20);

        $systemConfig = (new ConfigLogic())->getConfig('system');
        $this->view->systemConfig = $systemConfig;

        $this->getCategory();
    }

    protected function getCategory()
    {
        $category = (new ArticleLogic())->getCategoryTree();
        $this->view->navCategory = $category;
    }

}