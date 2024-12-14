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

namespace app\system\dao\store;


use app\mc\basic\BaseDao;
use app\system\model\store\SystemStoreStaff;

/**
 * 门店店员
 * Class SystemStoreStaffDao
 * @package app\system\dao\store
 */
class SystemStoreStaffDao extends BaseDao
{
    /**
     * 设置模型
     * @return string
     */
    protected function setModel(): string
    {
        return SystemStoreStaff::class;
    }

    /**
     * 获取店员列表
     * @param array $where
     * @param int $page
     * @param int $limit
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getStoreStaffList(array $where, int $page, int $limit)
    {
        return $this->search($where)->with(['store', 'user'])->page($page, $limit)->order('add_time DESC')->select()->toArray();
    }

}
