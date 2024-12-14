<?php
/**
 *  +----------------------------------------------------------------------
 *  | MC [ MC多应用系统，全产业链赋能 ]
 *  +----------------------------------------------------------------------
 *  | Copyright (c) 2022~2025 https://www.mc-serve.com All rights reserved.
 *  +----------------------------------------------------------------------
 *  | Licensed MC并不是自由软件，未经许可不能去掉MC相关版权
 *  +----------------------------------------------------------------------
 *  | Author: MC Team <cs@mc-serve.com>
 *  +----------------------------------------------------------------------
 */

namespace app\system\model;


use app\mc\basic\BaseModel;

/**
 * Class SystemCrud
 * @author 等风来
 * @email 136327134@qq.com
 * @date 2023/4/6
 * @package app\system\model
 */
class SystemCrud extends BaseModel
{

    /**
     * @var string
     */
    protected $name = 'system_crud';

    /**
     * @var string
     */
    protected $pk = 'id';

    public function getAddTimeAttr($value)
    {
        return $value;
    }

    public function getFieldAttr($value)
    {
        return json_decode($value, true);
    }

    public function getMenuIdsAttr($value)
    {
        return json_decode($value, true);
    }

    public function getMakePathAttr($value)
    {
        return json_decode($value, true);
    }

    public function getRouteIdsAttr($value)
    {
        return json_decode($value, true);
    }
}
