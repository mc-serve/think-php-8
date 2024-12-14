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
use app\system\model\config\SystemGroupData;

/**
 * 组合数据
 * Class SystemGroupDataDao
 * @package app\system\dao\config
 */
class SystemGroupDataDao extends BaseDao
{
    /**
     * 设置模型
     * @return string
     */
    protected function setModel(): string
    {
        return SystemGroupData::class;
    }

    /**
     * 获取组合数据列表
     * @param array $where
     * @param int $page
     * @param int $limit
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getGroupDataList(array $where, int $page, int $limit)
    {
        return $this->search($where)->page($page, $limit)->order('sort desc,id DESC')->select()->toArray();
    }

    /**
     * 获取某个system_group_id下的组合数据
     * @param int $system_group_id
     * @param int $limit
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getGroupDate(int $system_group_id, int $limit = 0)
    {
        return $this->search(['system_group_id' => $system_group_id, 'status' => 1])->when($limit, function ($query) use ($limit) {
            $query->limit($limit);
        })->field('value,id')->order('sort DESC,id DESC')->select()->toArray();
    }

    /**
     * 根据id获取秒杀数据
     * @param array $ids
     * @param string $field
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function idByGroupList(array $ids, string $field)
    {
        return $this->getModel()->whereIn('id', $ids)->field($field)->select()->toArray();
    }

    /**
     * 根据system_group_id删除组合数据
     * @param int $system_group_id
     * @return bool
     */
    public function delGroupDate(int $system_group_id)
    {
        return $this->getModel()->where('system_group_id', $system_group_id)->delete();
    }

    /**
     * 批量保存
     * @param array $data
     * @return mixed|\think\Collection
     * @throws \Exception
     */
    public function saveAll(array $data)
    {
        return $this->getModel()->saveAll($data);
    }
}
