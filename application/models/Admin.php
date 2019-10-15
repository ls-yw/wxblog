<?php

namespace application\models;

use woodlsy\phalcon\basic\BasicModel;

class Admin extends BasicModel
{
    /**
     * 表名
     *
     * @var string
     */
    protected $_targetTable = '{{admin}}';

    protected $_targetDb = 'blog';

    public function attribute()
    {
        return [
            'id'        => 'ID',
            'username'  => '用户名',
            'password'  => '密码错误',
            'create_at' => '创建时间',
            'update_at' => '更新时间',
            'create_by' => '创建人（记录管理员）',
            'update_by' => '更新人（记录管理员）',
        ];
    }
}