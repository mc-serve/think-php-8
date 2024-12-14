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
 * 组合数据配置模型
 * Class SystemGroup
 * @package app\system\model\config
 */
class SystemGroup extends BaseModel
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
    protected $name = 'system_group';

    /**
     * 配置名搜索器
     * @param Model $query
     * @param $value
     */
    public function searchConfigNameAttr($query, $value)
    {
        $query->where('config_name', $value);
    }

    /**
     * @param Model $query
     * @param $value
     */
    public function searchTitleAttr($query, $value)
    {
        if ($value != '') {
            $query->whereLIke('id|name|info|config_name', "%$value%");
        }
    }

    /**
     * 查询分类
     * @param Model $query
     * @param $value
     */
    public function searchCateIdAttr($query, $value)
    {
        $query->where('store_category_id', $value);
    }
}
