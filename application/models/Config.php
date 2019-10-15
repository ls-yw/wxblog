<?php

namespace application\models;

use woodlsy\phalcon\basic\BasicModel;

class Config extends BasicModel
{
    /**
     * 表名
     *
     * @var string
     */
    protected $_targetTable = '{{config}}';

    protected $_targetDb = 'blog';

    public function attribute()
    {
        return [
            'id'           => 'ID',
            'config_type'  => '类型',
            'config_name'  => '',
            'config_value' => '',
            'create_at'    => '创建时间',
            'update_at'    => '更新时间',
            'create_by'    => '创建人（记录管理员）',
            'update_by'    => '更新人（记录管理员）',
        ];
    }
}