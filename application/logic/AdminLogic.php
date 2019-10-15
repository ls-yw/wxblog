<?php
namespace application\logic;

use application\library\BlogException;
use application\models\Admin;
use woodlsy\phalcon\library\Helper;
use woodlsy\phalcon\library\Redis;

class AdminLogic
{
    /**
     * 管理员登录
     *
     * @author yls
     * @param string $username
     * @param string $password
     * @param int    $remember
     * @return string|null
     * @throws BlogException
     */
    public function login(string $username, string $password, int $remember)
    {
        $admin = (new Admin())->getOne(['username' => $username]);
        if (empty($admin)) {
            throw new BlogException('帐号不存在');
        }

        $token = crypt($password, md5($_SERVER['HTTP_HOST']));
        if ($token !== $admin['password']) {
            throw new BlogException('密码错误');
        }

        Redis::getInstance()->setex($token, ($remember ? 86400 * 30 : 86400), Helper::jsonEncode($admin));
        return $token;
    }
}