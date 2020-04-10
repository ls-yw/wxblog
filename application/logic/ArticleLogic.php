<?php
namespace application\logic;

use application\models\Admin;
use application\models\Article;
use application\models\ArticleTag;
use application\models\Category;
use library\BlogException;
use woodlsy\phalcon\library\Helper;
use woodlsy\phalcon\library\Redis;

class ArticleLogic
{
    /**
     * 获取分类
     *
     * @author yls
     * @param int|null $pid
     * @return array
     */
    public function getCategoryPairs(int $pid = null)
    {
        $where = ['is_deleted' => 0];
        if(null !== $pid) {
            $where['pid'] = $pid;
        }
        $res = (new Category())->getAll($where);
        $arr = [];
        if (empty($res)) {
            return $arr;
        }
        foreach ($res as $val) {
            $arr[$val['id']] = $val['name'];
        }
        return $arr;
    }

    /**
     * 根据ID获取详情
     *
     * @author yls
     * @param int $id
     * @return array|mixed
     */
    public function getCategoryById(int $id)
    {
        return (new Category())->getById($id);
    }

    /**
     * 根据ID获取详情
     *
     * @author yls
     * @param int $id
     * @return array|mixed
     */
    public function getArticleById(int $id)
    {
        return (new Article())->getById($id);
    }

    /**
     * 设置分类
     *
     * @author yls
     * @param array $data
     * @return bool|int
     */
    public function setCategory(array $data)
    {
        $id = $data['id'] ?? 0;
        unset($data['id']);
        if (empty($id)) {
            return (new Category())->insertData($data);
        } else {
            return (new Category())->updateData($data, ['id' => $id]);
        }
    }

    /**
     * 保存文章
     *
     * @author yls
     * @param array $data
     * @return bool
     * @throws \application\library\BlogException
     */
    public function setArticle(array $data)
    {
        $id = $data['id'] ?? 0;
        unset($data['id']);
        if (empty($id)) {
            $id = (new Article())->insertData($data);
            if (empty($id)) {
                return false;
            }
            if (!empty($data['tags'])) {
                foreach (explode(',', $data['tags']) as $val) {
                    (new ArticleTag())->insertData(['article_id' => $id, 'tag' => $val]);
                }
            }
        } else {
            $article = (new Article())->getById($id);
            if (!$article) {
                throw new \application\library\BlogException('文章不存在');
            }
            $row = (new Article())->updateData($data, ['id' => $id]);
            if (empty($row)) {
                return false;
            }
            if ($article['tags'] !== $data['tags']) {
                (new ArticleTag())->delData(['article_id' => $id]);
                if (!empty($data['tags'])) {
                    foreach (explode(',', $data['tags']) as $val) {
                        (new ArticleTag())->insertData(['article_id' => $id, 'tag' => $val]);
                    }
                }
            }
        }
        return true;

    }

    /**
     * 获取分类
     *
     * @author yls
     * @return array|bool
     */
    public function getCategory()
    {
        return (new Category())->getAll(['is_deleted' => 0]);
    }

    /**
     * 获取文章列表
     *
     * @author yls
     * @param int    $page
     * @param int    $size
     * @param string $orderBy
     * @return array|bool
     */
    public function getArticle(int $page, int $size = 20, string $orderBy = 'id desc')
    {
        $offset = ($page - 1) * $size;
        return (new Article())->getList(['is_deleted' => 0, 'is_push' => 1], $orderBy, $offset, $size);
    }

    /**
     * 文章条数
     *
     * @author yls
     * @return array|int
     */
    public function getArticleCount()
    {
        return (new Article())->getCount(['is_deleted' => 0]);
    }

    /**
     * 搜索文章
     *
     * @author yls
     * @param string $keyword
     * @param int    $page
     * @param int    $size
     * @param string $orderBy
     * @return array|bool
     */
    public function getArticleBySearch(string $keyword, int $page, int $size = 20, string $orderBy = 'id desc')
    {
        $offset = ($page - 1) * $size;
        return (new Article())->getList(['is_deleted' => 0, 'title' => ['like', '%'.$keyword.'%']], $orderBy, $offset, $size);
    }

    /**
     * 搜索文章条数
     *
     * @author yls
     * @param string $keyword
     * @return array|bool
     */
    public function getArticleBySearchCount(string $keyword)
    {
        return (new Article())->getCount(['is_deleted' => 0, 'title' => ['like', '%'.$keyword.'%']]);
    }

    /**
     * 增加阅读量
     *
     * @author yls
     * @param int $id
     */
    public function addArticleClick(int $id)
    {
        (new Article())->updateData(['clicks' => ['+', 1]], ['id' => $id]);
    }
}