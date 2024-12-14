<?php

namespace app\system\model\log;

use app\mc\basic\BaseModel;
use app\mc\traits\ModelTrait;

/**
 * @author 扫地僧
 * @email cto@mc-serve.com
 * @date 2023/04/07
 */
class SystemFileInfo extends BaseModel
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
    protected $name = 'system_file_info';
}