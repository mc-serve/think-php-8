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
 * Class SystemCrudData
 * @author 等风来
 * @email 136327134@qq.com
 * @date 2023/7/28
 * @package app\system\model
 */
class SystemCrudData extends BaseModel
{
    /**
     * @var string
     */
    protected $name = 'system_crud_data';

    /**
     * @var string
     */
    protected $pk = 'id';

    public function getValueAttr($value)
    {
        return json_decode($value, true);
    }

    /**
     * @param $query
     * @param $value
     * @author 等风来
     * @email 136327134@qq.com
     * @date 2023/8/10
     */
    public function searchNameAttr($query, $value)
    {
        if ($value != '') {
            $query->where('name', 'like', '%' . $value . '%');
        }
    }
}
