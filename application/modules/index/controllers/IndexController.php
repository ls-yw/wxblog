<?php

namespace application\modules\index\controllers;

use application\base\BaseController;
use application\logic\ArticleLogic;
use woodlsy\phalcon\library\Redis;

class IndexController extends BaseController
{
    public function indexAction()
    {
        $articles = (new ArticleLogic())->getArticle(1);

        $this->view->articles      = $articles;
        $this->view->clickArticles = (new ArticleLogic())->getArticle(1, 5, 'clicks desc');
    }

    public function listAction()
    {
        $page     = (int) $this->get('page', 'int', 1);
        $articles = (new ArticleLogic())->getArticle($page);

        $this->view->articles      = $articles;
        $this->view->articleCount  = (new ArticleLogic())->getArticleCount();
        $this->view->clickArticles = (new ArticleLogic())->getArticle(1, 5, 'clicks desc');
    }

    public function infoAction()
    {
        $id      = (int) $this->get('id', 'int');
        $article = (new ArticleLogic())->getArticleById($id);

        $ip  = $this->request->getClientAddress();
        $key = md5($id . '_' . $ip);
        if (!Redis::getInstance()->exists($key)) {
            (new ArticleLogic())->addArticleClick($id);
            $article['clicks']++;
            Redis::getInstance()->setex($key, 120, 1);
        }

        $tags = !empty($article['tags']) ? explode(',', $article['tags']) : '';

        $this->view->title       = $article['title'];
        $this->view->keywords    = $article['tags'];
        $this->view->description = $article['desc'];

        $this->view->article       = $article;
        $this->view->tags          = $tags;
        $this->view->clickArticles = (new ArticleLogic())->getArticle(1, 5, 'clicks desc');
    }

    public function searchAction()
    {
        $page    = (int) $this->get('page', 'int', 1);
        $keyword = $this->get('search', 'string', '');

        $articles = (new ArticleLogic())->getArticleBySearch($keyword, $page);

        $articles                  = (new ArticleLogic())->getArticle($page);
        $this->view->articles      = $articles;
        $this->view->articleCount  = (new ArticleLogic())->getArticleBySearchCount($keyword);
        $this->view->clickArticles = (new ArticleLogic())->getArticle(1, 5, 'clicks desc');
    }
}