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

namespace app\system\dao\admin;

use app\mc\basic\BaseDao;
use app\system\model\admin\SystemAdmin;

/**
 * Class SystemAdminDao
 * @package app\system\dao\admin
 */
class SystemAdminDao extends BaseDao
{
    protected function setModel(): string
    {
        return SystemAdmin::class;
    }

    /**
     * 获取管理员列表
     * @param array $where
     * @param int $page
     * @param int $limit
     * @return mixed
     */
    public function getList(array $where, int $page, int $limit)
    {
        return $this->search($where)->page($page, $limit)->select()->toArray();
    }

    /**
     * 用管理员名查找管理员信息
     * @param string $account
     * @return array|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function accountByAdmin(string $account)
    {
        return $this->search(['account' => $account, 'is_del' => 0])->find();
    }

    /**
     * 当前账号是否可用
     * @param string $account
     * @param int $id
     * @return int
     */
    public function isAccountUsable(string $account, int $id)
    {
        return $this->search(['account' => $account, 'is_del' => 0])->where('id', '<>', $id)->count();
    }

    /**
     * 获取adminid
     * @param int $level
     * @return array
     */
    public function getAdminIds(int $level)
    {
        return $this->getModel()->where('level', '>=', $level)->column('id', 'id');
    }

    /**
     * 获取低于等级的管理员名称和id
     * @param string $field
     * @param int $level
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getOrdAdmin(string $field = 'real_name,id', int $level = 0)
    {
        return $this->getModel()->where('level', '>=', $level)->field($field)->select()->toArray();
    }

    /**
     * 条件获取管理员数据
     * @param $where
     * @return mixed
     */
    public function getInfo($where)
    {
        return $this->getModel()->where($where)->find();
    }

    /**
     * 检测是否有管理员使用该角色
     * @param int $id
     * @return bool
     */
    public function checkRoleUse(int $id): bool
    {
        return (bool)$this->getModel()->where('is_del', 0)->whereFindInSet('roles', $id)->count();
    }
}
