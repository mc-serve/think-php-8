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

namespace app\system\model\config;

use app\mc\basic\BaseModel;
use app\mc\traits\ModelTrait;
use think\Model;

/**
 * 组合数据数据列表模型
 * Class SystemGroupData
 * @package app\system\model\config
 */
class SystemGroupData extends BaseModel
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
    protected $name = 'system_group_data';

    /**
     * 状态搜索器
     * @param $query
     * @param $value
     */
    public function searchStatusAttr($query, $value)
    {
        if ($value != '') {
            $query->where('status', $value);
        }
    }

    /**
     * Gid搜索器
     * @param Model $query
     * @param $value
     */
    public function searchGidAttr($query, $value)
    {
        if (is_array($value)) {
            $query->whereIn('gid', $value);
        } else {
            $query->where('gid', $value);
        }
    }
}
