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
declare (strict_types=1);

namespace app\system\dao;

use app\mc\basic\BaseDao;
use app\system\model\SystemNotification;

/**
 *
 * Class SystemUserLevelDao
 * @package app\system\dao
 */
class SystemNotificationDao extends BaseDao
{

    /**
     * 设置模型
     * @return string
     */
    protected function setModel(): string
    {
        return SystemNotification::class;
    }

    /**
     * 获取列表
     * @param array $where
     * @param string $field
     * @param int $page
     * @param int $limit
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author: 扫地僧
     * @email: cto@mc-serve.com
     * @date: 2023/8/16
     */
    public function getList(array $where, string $field = '*', int $page = 0, $limit = 0)
    {
        return $this->getModel()->where($where)->field($field)->when($page && $limit, function ($query) use ($page, $limit) {
            $query->page($page, $limit);
        })->order('id asc')->select()->toArray();
    }

    /**
     * 获取tempid
     * @param $type
     * @return array
     * @author: 扫地僧
     * @email: cto@mc-serve.com
     * @date: 2023/8/16
     */
    public function getTempId($type)
    {
        $whereField = 'is_' . $type;
        $field = $type . '_tempid';
        return array_unique($this->getModel()->where($whereField, 1)->where($field, '<>', '')->column($field));
    }

    /**
     * 获取tempkey
     * @param $type
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author: 扫地僧
     * @email: cto@mc-serve.com
     * @date: 2023/8/16
     */
    public function getTempKey($type)
    {
        $whereField = 'is_' . $type;
        $field = $type . '_tempkey';
        $content = $type . '_content,name';
        return $this->getModel()->where($whereField, 1)->column($content, $field);
    }

}
