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

namespace app\system\model\statistics;

use app\mc\basic\BaseModel;
use app\mc\traits\ModelTrait;

class CapitalFlow extends BaseModel
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
    protected $name = 'capital_flow';

    /**
     * 交易类型搜索器
     * @param $query
     * @param $value
     */
    public function searchTradingTypeAttr($query, $value)
    {
        if ($value) $query->where('trading_type', $value);
    }

    /**
     * 关键字搜索器
     * @param $query
     * @param $value
     */
    public function searchKeywordsAttr($query, $value)
    {
        if ($value !== '') $query->where('order_id|user_id|nickname|phone', 'like', '%' . $value . '%');
    }

    /**
     * 批量id搜索器
     * @param $query
     * @param $value
     */
    public function searchIdsAttr($query, $value)
    {
        if ($value != '') $query->whereIn('id', $value);
    }
}
