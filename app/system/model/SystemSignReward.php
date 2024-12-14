<?php

namespace app\system\model;

use app\mc\basic\BaseModel;
use app\mc\traits\ModelTrait;

/**
 * @author: 扫地僧
 * @email: cto@mc-serve.com
 * @date: 2023/7/28
 */
class SystemSignReward extends BaseModel
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
    protected $name = 'system_sign_reward';

    /**
     * 类型搜索器
     * @param $query
     * @param $value
     * @author: 扫地僧
     * @email: cto@mc-serve.com
     * @date: 2023/7/28
     */
    public function searchTypeAttr($query, $value)
    {
        if ($value !== '') $query->where('type', $value);
    }
}