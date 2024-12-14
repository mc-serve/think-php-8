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
use app\system\model\config\SystemStorage;

/**
 * Class SystemStorageDao
 * @package app\system\dao\config
 */
class SystemStorageDao extends BaseDao
{

    /**
     * @return string
     */
    protected function setModel(): string
    {
        return SystemStorage::class;
    }

    /**
     * 获取列表
     * @param array $where
     * @param array|string[] $field
     * @param int $page
     * @param int $limit
     * @param null $sort
     * @param array $with
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getList(array $where = [], array $field = ['*'], int $page = 0, int $limit = 0, $sort = null, array $with = [])
    {
        return $this->search($where)->field($field)->when($page && $limit, function ($query) use ($page, $limit) {
            $query->page($page, $limit);
        })->when($sort, function ($query) use ($sort) {
            if (is_array($sort)) {
                foreach ($sort as $v => $k) {
                    if (is_numeric($v)) {
                        $query->order($k, 'desc');
                    } else {
                        $query->order($v, $k);
                    }
                }
            } else {
                $query->order($sort, 'desc');
            }
        })->with($with)->select()->toArray();
    }

    /**
     * @param array $where
     * @param bool $search
     * @return \app\mc\basic\BaseModel|mixed|\think\Model
     * @throws \ReflectionException
     */
    public function search(array $where = [], bool $search = false)
    {
        return parent::search($where, $search)->when(isset($where['type']), function ($query) use ($where) {
            $query->where('type', $where['type']);
        })->where('is_delete', 0)->when(isset($where['access_key']), function ($query) use ($where) {
            $query->where('access_key', $where['access_key']);
        })->when(!empty($where['id']), function ($query) use ($where) {
            $query->where('id', $where['id']);
        });
    }
}
