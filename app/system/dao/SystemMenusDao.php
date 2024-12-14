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

namespace app\system\dao;

use app\mc\basic\BaseDao;
use app\system\model\SystemMenus;

/**
 * 菜单dao层
 * Class SystemMenusDao
 * @package app\system\dao
 */
class SystemMenusDao extends BaseDao
{

    /**
     * 设置模型
     * @return string
     */
    protected function setModel(): string
    {
        return SystemMenus::class;
    }

    /**
     * @param array $menusIds
     * @return bool
     * @author 等风来
     * @email 136327134@qq.com
     * @date 2023/4/13
     */
    public function deleteMenus(array $menusIds)
    {
        return $this->getModel()->whereIn('id', $menusIds)->delete();
    }

    /**
     * 获取权限菜单列表
     * @param array $where
     * @param array $field
     * @return \think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getMenusRoule(array $where, ?array $field = [])
    {
        if (!$field) {
            $field = ['id', 'menu_name', 'icon', 'pid', 'sort', 'menu_path', 'is_show', 'header', 'is_header', 'is_show_path', 'is_show'];
        }
        return $this->search($where)->field($field)->order('sort DESC,id DESC')->failException(false)->select();
    }

    /**
     * 获取菜单中的唯一权限
     * @param array $where
     * @return array
     */
    public function getMenusUnique(array $where)
    {
        return $this->search($where)->where('unique_auth', '<>', '')->column('unique_auth', '');
    }

    /**
     * 根据访问地址获得菜单名
     * @param string $rule
     * @return mixed
     */
    public function getVisitName(string $rule)
    {
        return $this->search(['url' => $rule])->value('menu_name');
    }

    /**
     * 获取后台菜单列表并分页
     * @param array $where
     * @return \think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getMenusList(array $where, array $field = ['*'])
    {
        $where = array_merge($where, ['is_del' => 0]);
        return $this->search($where)->field($field)->order('sort DESC,id ASC')->select();
    }

    /**
     * 菜单总数
     * @param array $where
     * @return int
     */
    public function countMenus(array $where)
    {
        $where = array_merge($where, ['is_del' => 0]);
        return $this->count($where);
    }

    /**
     * 指定条件获取某些菜单的名称以数组形式返回
     * @param array $where
     * @param string $field
     * @param string $key
     * @return array
     */
    public function column(array $where, string $field, string $key = '')
    {
        return $this->search($where)->column($field, $key);
    }

    /**菜单列表
     * @param array $where
     * @param int $type
     * @return \think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function menusSelect(array $where, $type = 1)
    {
        if ($type == 1) {
            return $this->search($where)->field('id,pid,menu_name,menu_path,unique_auth,sort')->order('sort DESC,id DESC')->select();
        } else {
            return $this->search($where)->group('pid')->column('pid');
        }
    }

    /**
     * 搜索列表
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getSearchList()
    {
        return $this->search(['is_show' => 1, 'auth_type' => 1, 'is_del' => 0, 'is_show_path' => 0])
            ->field('id,pid,menu_name,menu_path,unique_auth,sort')->order('sort DESC,id DESC')->select();
    }

    /**
     * @param string $path
     * @param string $method
     * @return bool
     * @author 等风来
     * @email 136327134@qq.com
     * @date 2023/4/20
     */
    public function deleteMenu(string $path, string $method)
    {
        return $this->getModel()->where('api_url', $path)->where('methods', $method)->delete();
    }
}
