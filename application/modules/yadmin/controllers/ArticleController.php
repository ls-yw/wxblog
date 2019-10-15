<?php

namespace application\modules\yadmin\controllers;

use application\base\AdminBaseController;
use application\library\BlogException;
use application\logic\ArticleLogic;
use Exception;
use woodlsy\phalcon\library\Log;
use woodlsy\phalcon\library\Redis;
use woodlsy\upload\Upload;

class ArticleController extends AdminBaseController
{

    /**
     * 文章列表
     *
     * @author yls
     */
    public function IndexAction()
    {
        $page     = (int) $this->get('page', 'int', 1);
        $article  = (new ArticleLogic())->getArticle($page, 20);
        $category = (new ArticleLogic())->getCategoryPairs(0);

        $this->view->menuflag = 'article';
        $this->view->title    = '文章列表';
        $this->view->data     = $article;
        $this->view->category = $category;
    }

    /**
     * 编辑文章
     *
     * @author yls
     * @return \Phalcon\Http\ResponseInterface
     */
    public function setArticleAction()
    {
        if ($this->request->isPost()) {
            try {
                $id         = $this->post('id');
                $title      = $this->post('title');
                $categoryId = $this->post('category_id');
//                $desc       = $this->post('desc');
                $content = $this->post('content');
                $tags    = $this->post('tags');
                $isPush  = $this->post('is_push');
                $sort    = $this->post('sort');

                if (empty($title)) {
                    throw new BlogException('标题不能为空');
                }

                $imgUrl = '';
                if (0 === (int) $_FILES['file']['error']) {
                    $uploadInfo = (new Upload())->setServerUrl($this->config->uploadUrl)->Upload();
                    $imgUrl     = $this->config->uploadPath . $uploadInfo['url'];
                }

                $data = [
                    'id'          => $id,
                    'title'       => $title,
                    'category_id' => $categoryId,
                    //                    'desc'       => $desc,
                    'content'     => $content,
                    'tags'        => $tags,
                    'is_push'     => $isPush,
                    'sort'        => $sort,
                    'img_url'     => $imgUrl,
                ];

                $row = (new ArticleLogic())->setArticle($data);
                if (!$row) {
                    throw new BlogException('保存失败');
                }
                return $this->response->redirect('/yadmin/article/index.html');
            } catch (BlogException $e) {
                Redis::getInstance()->setex('alert_error', 3600, $e->getMessage());
                return $this->response->redirect('/yadmin/article/setArticle.html');
            } catch (Exception $e) {
                Log::write($this->controllerName . '|' . $this->actionName, $e->getMessage() . $e->getFile() . $e->getLine(), 'error');
                Redis::getInstance()->setex('alert_error', 3600, '系统错误');
                return $this->response->redirect('/yadmin/article/setArticle.html');
            }
        } else {
            $id = (int) $this->get('id');

            $parentCategory       = (new ArticleLogic())->getCategoryPairs();
            $this->view->menuflag = 'article';
            $this->view->title    = '设置文章';
            $this->view->category = $parentCategory;
            $this->view->data     = empty($id) ? '' : (new ArticleLogic())->getArticleById($id);
        }
    }

    /**
     * 分类列表
     *
     * @author yls
     */
    public function CategoryAction()
    {
        $category       = (new ArticleLogic())->getCategory();
        $parentCategory = (new ArticleLogic())->getCategoryPairs(0);

        $this->view->menuflag = 'article-category';
        $this->view->title    = '文章分类';
        $this->view->data     = $category;
        $this->view->category = $parentCategory;
    }

    /**
     * 设置分类
     *
     * @author yls
     * @return \Phalcon\Http\ResponseInterface
     */
    public function SetCategoryAction()
    {
        if ($this->request->isPost()) {
            try {
                $id        = $this->post('id');
                $name      = $this->post('name');
                $pid       = $this->post('pid');
                $isDeleted = (int) $this->post('is_deleted');

                if (empty($name)) {
                    throw new BlogException('分类名称不能为空');
                }

                $data = [
                    'id'         => $id,
                    'name'       => $name,
                    'pid'        => $pid,
                    'is_deleted' => $isDeleted,
                ];

                $row = (new ArticleLogic())->setCategory($data);
                if (!$row) {
                    throw new BlogException('保存失败');
                }
                return $this->response->redirect('/yadmin/article/category.html');
            } catch (BlogException $e) {
                Redis::getInstance()->setex('alert_error', 3600, $e->getMessage());
                return $this->response->redirect('/yadmin/article/setCategory.html');
            } catch (Exception $e) {
                Log::write($this->controllerName . '|' . $this->actionName, $e->getMessage() . $e->getFile() . $e->getLine(), 'error');
                Redis::getInstance()->setex('alert_error', 3600, '系统错误');
                return $this->response->redirect('/yadmin/article/setCategory.html');
            }
        } else {
            $id = (int) $this->get('id');

            $parentCategory       = (new ArticleLogic())->getCategoryPairs(0);
            $this->view->menuflag = 'article-category';
            $this->view->title    = '设置分类';
            $this->view->category = $parentCategory;
            $this->view->data     = empty($id) ? '' : (new ArticleLogic())->getCategoryById($id);
        }
    }


}
