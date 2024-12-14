<?php

namespace app\system\model\crontab;

use app\mc\basic\BaseModel;
use app\mc\traits\ModelTrait;

class SystemCrontab extends BaseModel
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
    protected $name = 'system_timer';
}