<?php
namespace application\controllers;

use application\base\BaseController;
use application\library\BlogException;
use application\logic\ArticleLogic;

class ArticleController extends BaseController
{
    public function indexAction()
    {
        try {
            $id = (int)$this->get('id');
            if (empty($id)) {
                throw new BlogException('内容不存在');
            }

            $article = (new ArticleLogic())->getArticleById($id);
            if (empty($article)) {
                throw new BlogException('内容不存在');
            }

            $category = (new ArticleLogic())->getCategoryById($article['category_id']);

            $ids = $this->cookies->get('articleClick')->getValue();
            $idArray = explode('|', $ids);
            if (!in_array($id, $idArray)) {
                (new ArticleLogic())->addArticleClick($id);
                $article['clicks']++;
                $idArray[] = $id;
                $this->cookies->set('articleClick', implode('|', $idArray), time() + 3600);
            }

            $crumbs = [];
            $crumbs[] = ['name' => $category['name'], 'url' => '/category/index.html?id='.$category['id']];

            $this->view->clickArticles = (new ArticleLogic())->getArticle($article['category_id'], 1, 5, 'clicks desc');
            $this->view->title = $article['title'];
            $this->view->seoTitle = $article['title'];
            $this->view->keywords = $article['tags'];
            $this->view->description = $article['desc'];
            $this->view->crumbs = $crumbs;
            $this->view->article = $article;
        } catch (BlogException $e) {
            $this->dispatcher->forward([
                'controller' => 'index',
                'action'     => 'error',
                'params'     => [$e->getMessage()]
            ]);
            return;
        }
    }
}
