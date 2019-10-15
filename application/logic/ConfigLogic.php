<?php
namespace application\logic;

use application\models\Config;

class ConfigLogic
{
    /**
     * 获取配置
     *
     * @author yls
     * @param string      $type
     * @param string|null $name
     * @return array
     */
    public function getConfig(string $type, string $name = null)
    {
        $where = ['config_type' => $type];
        if (null !== $name) {
            $where['config_name'] = $name;
        }
        $configs = (new Config())->getAll($where);
        $arr = [];
        if (!empty($configs)) {
            foreach ($configs as $val) {
                $arr[$val['config_name']] = $val['config_value'];
            }
        }
        return $arr;
    }
}