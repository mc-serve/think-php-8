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

/**
 * Class AppVersion
 * @package app\system\model
 */
class AppVersion extends BaseModel
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
    protected $name = 'app_version';

    public function searchPlatformAttr($query, $value, $data)
    {
        if($value!='') $query->where('platform',$value);
    }
}