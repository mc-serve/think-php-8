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
use app\system\model\store\SystemStore;

/**
 * 门店dao
 * Class SystemStoreDao
 * @package app\system\dao\store
 */
class SystemStoreDao extends BaseDao
{
    /**
     * 设置模型
     * @return string
     */
    protected function setModel(): string
    {
        return SystemStore::class;
    }

    /**
     * 经纬度排序计算
     * @param string $latitude
     * @param string $longitude
     * @return string
     */
    public function distance(string $latitude, string $longitude)
    {
        return "(round(6367000 * 2 * asin(sqrt(pow(sin(((latitude * pi()) / 180 - ({$latitude} * pi()) / 180) / 2), 2) + cos(({$latitude} * pi()) / 180) * cos((latitude * pi()) / 180) * pow(sin(((longitude * pi()) / 180 - ({$longitude} * pi()) / 180) / 2), 2))))) AS distance";
    }

    /**
     * 获取
     * @param array $where
     * @param int $page
     * @param int $limit
     * @param string $latitude
     * @param string $longitude
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getStoreList(array $where, array $field, int $page = 0, int $limit = 0, string $latitude = '', string $longitude = '')
    {
        return $this->search($where)->when($longitude && $latitude, function ($query) use ($longitude, $latitude) {
            $query->field(['*', $this->distance($latitude, $longitude)])->order('distance ASC');
        })->when($page && $limit, function ($query) use ($page, $limit) {
            $query->page($page, $limit);
        })->field($field)->order('id desc')->select()->toArray();
    }

    /**
     * 获取门店不分页
     * @param array $where
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getStore(array $where)
    {
        return $this->search($where)->order('add_time DESC')->field(['id', 'name'])->select()->toArray();
    }
}
