<?php

namespace application\controllers;

use application\base\BaseController;
use application\logic\ArticleLogic;

class IndexController extends BaseController
{
    public function indexAction()
    {
        $articles = (new ArticleLogic())->getArticle(1);

        $this->view->articles      = $articles;
        $this->view->clickArticles = (new ArticleLogic())->getArticle(1, 5, 'clicks desc');
    }


}