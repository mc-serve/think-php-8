<?php
// +----------------------------------------------------------------------
// | MC [ MC多应用系统，全产业链赋能 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2022~2025 https://www.mc-serve.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed MC并不是自由软件，未经许可不能去掉MC相关版权
// +----------------------------------------------------------------------
// | Author: MC Team <cs@mc-serve.com>
// +----------------------------------------------------------------------

namespace app\system\dao\config;

use app\mc\basic\BaseDao;
use app\system\model\config\SystemGroup;

/**
 * Class SystemGroupDao
 * @package app\system\dao\config
 */
class SystemGroupDao extends BaseDao
{
    /**
     * @return string
     */
    protected function setModel(): string
    {
        return SystemGroup::class;
    }

    /**
     * 获取组合数据分页列表
     * @param array $where
     * @param int $page
     * @param int $limit
     * @return \think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getGroupList(array $where, array $field = ['*'], int $page = 0, int $limit = 0)
    {
        return $this->search($where)->field($field)->when($page && $limit, function ($query) use ($page, $limit) {
            $query->page($page, $limit);
        })->select()->toArray();
    }

    /**
     * 根据配置名称获取配置id
     * @param string $configName
     * @return mixed
     */
    public function getConfigNameId(string $configName)
    {
        return $this->value(['config_name' => $configName]);
    }
}
