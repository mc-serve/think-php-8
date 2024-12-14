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

namespace app\system\model;

use app\mc\basic\BaseModel;
use app\mc\traits\ModelTrait;
use think\Model;

/**
 * 系统等级设置模型
 * Class SystemUserLevel
 * @package app\system\model
 */
class MessageSystem extends BaseModel
{
    use ModelTrait;

    /**
     * 数据表主键
     * @var string
     */
    protected $pk = 'id';


    /**
     * 模型名称
     * @var string
     */
    protected $name = 'system_message';


    protected $insert = ['add_time'];

    /**
     * ID搜索器
     * @param Model $query
     * @param $value
     * @param $data
     */
    public function searchIdAttr($query, $value, $data)
    {
        $query->where('id', $value);
    }
    /**
     * UID搜索器
     * @param Model $query
     * @param $value
     * @param $data
     */
    public function searchUserIdAttr($query, $value, $data)
    {
        $query->where('user_id', $value);
    }
    /**
     * Look搜索器
     * @param Model $query
     * @param $value
     * @param $data
     */
    public function searchLookAttr($query, $value, $data)
    {
        $query->where('look', $value);
    }
    /**
     * del搜索器
     * @param Model $query
     * @param $value
     * @param $data
     */
    public function searchIsDelAttr($query, $value, $data)
    {
        $query->where('is_del', $value);
    }

}
