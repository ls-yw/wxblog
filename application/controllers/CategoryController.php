<?php
namespace application\controllers;

use application\base\BaseController;
use application\logic\ArticleLogic;

class CategoryController extends BaseController
{
    public function indexAction()
    {
        $id = (int)$this->get('id');
        $articles = (new ArticleLogic())->getArticle($id, $this->page);

        $category = (new ArticleLogic())->getCategoryById($id);

        $this->view->articles      = $articles;
        $this->view->articleCount  = (new ArticleLogic())->getArticleCount($id);
        $this->view->clickArticles = (new ArticleLogic())->getArticle(0, 1, 5, 'clicks desc');

        $this->view->title = $category['name'];
        $this->view->seoTitle = $category['name'];
        $this->view->keywords = $category['name'];
        $this->view->description = $category['name'];
    }

    public function searchAction()
    {
        $keyword = $this->get('keyword', 'string', '');

        $articles = [];
        if (!empty($keyword)) {
            $articles = (new ArticleLogic())->getArticleBySearch($keyword, $this->page);
        }

        $crumbs = [];
        $crumbs[] = ['name' => '搜索'];

        $this->view->articles      = $articles;
        $this->view->articleCount  = (new ArticleLogic())->getArticleBySearchCount($keyword);
        $this->view->crumbs = $crumbs;
        $this->view->title = '<span class="red">'.$keyword.'</span> 的结果';
        $this->view->seoTitle = '搜索'.$keyword.'的结果';
        $this->view->keywords = '搜索'.$keyword.'的结果';
        $this->view->description = '搜索'.$keyword.'的结果';
    }
}