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

namespace app\system\model\store;

use app\model\user\User;
use app\mc\basic\BaseModel;
use app\mc\traits\ModelTrait;
use think\Model;

/**
 * 店员模型
 * Class SystemStoreStaff
 * @package app\system\model\store
 */
class SystemStoreStaff extends BaseModel
{
    use ModelTrait;

    /**
     * 模型名称
     * @var string
     */
    protected $name = 'system_store_staff';

    /**
     * user用户表一对一关联
     * @return \think\model\relation\HasOne
     */
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id')->field(['id', 'nickname'])->bind([
            'nickname' => 'nickname'
        ]);
    }

    /**
     * 门店表一对一关联
     * @return \think\model\relation\HasOne
     */
    public function store()
    {
        return $this->hasOne(SystemStore::class, 'id', 'store_id')->field(['id', 'name'])->bind([
            'name' => 'name'
        ]);
    }

    /**
     * 时间戳获取器转日期
     * @param $value
     * @return false|string
     */
    public static function getAddTimeAttr($value)
    {
        return $value;
    }

    /**
     * 是否有核销权限搜索器
     * @param Model $query
     * @param $value 用户user_id
     */
    public function searchIsStatusAttr($query, $value)
    {
        $query->where(['user_id' => $value, 'status' => 1, 'verify_status' => 1]);
    }

    /**
     * user_id搜索器
     * @param Model $query
     * @param $value
     */
    public function searchUserIdAttr($query, $value)
    {
        $query->where('user_id', $value);
    }

    /**
     * 门店id搜索器
     * @param Model $query
     * @param $value
     */
    public function searchSystemStoreIdAttr($query, $value)
    {
        if ($value && $value > 0) {
            $query->where('system_store_id', $value);
        }
    }
}
