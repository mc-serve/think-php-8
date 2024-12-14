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

namespace app\system\model\log;

use app\mc\basic\BaseModel;
use app\mc\traits\ModelTrait;
use think\Model;

/**
 * 日志模型
 * Class SystemLog
 * @package app\system\model\log
 */
class SystemLog extends BaseModel
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
    protected $name = 'system_log';

    protected $insert = ['add_time'];

    /**
     * 访问方式搜索器
     * @param Model $query
     * @param $value
     */
    public function searchPagesAttr($query, $value)
    {
        if ($value !== '') {
            $query->whereLike('page', '%' . $value . '%');
        }
    }

    /**
     * 访问路径搜索器
     * @param Model $query
     * @param $value
     */
    public function searchPathAttr($query, $value)
    {
        if ($value !== '') {
            $query->whereLike('path', '%' . $value . '%');
        }
    }

    /**
     * ip搜索器
     * @param Model $query
     * @param $value
     */
    public function searchIpAttr($query, $value)
    {
        if ($value !== '') {
            $query->where('ip', 'LIKE', "%$value%");
        }
    }

    /**
     * 管理员id搜索器
     * @param Model $query
     * @param $value
     */
    public function searchAdminIdAttr($query, $value)
    {
        if (!empty($value)) {
            $query->whereIn('admin_id', $value);
        }
    }
}
