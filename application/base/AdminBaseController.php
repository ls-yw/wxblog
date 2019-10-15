<?php
namespace application\base;

use woodlsy\phalcon\basic\BasicController;
use woodlsy\phalcon\library\Helper;
use woodlsy\phalcon\library\Redis;
use Exception;

class AdminBaseController extends BasicController
{

    public $token = null;
    public $admin = null;

    /**
     * 初始化
     *
     * @author yls
     * @throws Exception
     */
    public function initialize()
    {
        parent::initialize();

        $this->token = $this->cookies->get('token')->getValue();
        $this->setAdmin();

        $this->checkLogin();

        $menus = $this->getMenu();
        $this->view->menus = $menus;

        $alertError = Redis::getInstance()->get('alert_error');
        if (!empty($alertError)) {
            $this->view->alertError = $alertError;
            Redis::getInstance()->del('alert_error');
        }
    }

    /**
     * 获取登录信息
     *
     * @author yls
     */
    private function setAdmin()
    {
        if (!$this->token) {
            return;
        }
        if (Redis::getInstance()->exists($this->token)) {
            $admin = Redis::getInstance()->get($this->token);
            $this->admin = Helper::jsonDecode($admin);
        }
    }

    /**
     * 检测是否登录
     *
     * @author yls
     */
    private function checkLogin() : void
    {
        if ('login' === $this->router->getControllerName()) {
            return;
        }
        if (empty($this->admin)) {
            $this->response->redirect('admin/login/index.html');
        }
    }

    private function getMenu(){
        return [
            ['title'=>'工作台', 'icon'=>'fa fa-dashboard', 'link'=>'/yadmin/index/index.html', 'flag'=>'workbench'],
            ['title'=>'文章管理', 'icon'=>'fa fa-book', 'link'=>'#','children'=>[
                ['title'=>'文章分类', 'link'=>'/yadmin/article/category.html', 'flag'=>'article-category'],
                ['title'=>'文章列表', 'link'=>'/yadmin/article/index.html', 'flag'=>'article'],
            ]],
            ['title'=>'配置管理', 'icon'=>'fa fa-gears', 'link'=>'#','children'=>[
                ['title'=>'渠道管理', 'link'=>'/backend/channel/index', 'flag'=>'channel'],
            ]],
        ];
    }
}