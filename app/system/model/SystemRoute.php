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
use think\model\concern\SoftDelete;

/**
 * Class SystemRoute
 * @author 等风来
 * @email 136327134@qq.com
 * @date 2023/4/6
 * @package app\system\model
 */
class SystemRoute extends BaseModel
{

    use SoftDelete;

    /**
     * @var string
     */
    protected $name = 'system_route';

    /**
     * @var string
     */
    protected $key = 'id';

    public function searchNameLikeAttr($query, $value)
    {
        if ('' !== $value) {
            $query->where('name|path', 'LIKE', '%' . $value . '%');
        }
    }

    public function setQueryAttr($value)
    {
        return json_encode($value);
    }

    public function getQueryAttr($value)
    {
        return json_decode($value, true);
    }

    public function setHeaderAttr($value)
    {
        return json_encode($value);
    }

    public function getHeaderAttr($value)
    {
        return json_decode($value, true);
    }

    public function setRequestAttr($value)
    {
        return json_encode($value);
    }

    public function getRequestAttr($value)
    {
        return json_decode($value, true);
    }

    public function setResponseAttr($value)
    {
        return json_encode($value);
    }

    public function getResponseAttr($value)
    {
        return json_decode($value, true);
    }

    public function setRequestExampleAttr($value)
    {
        return json_encode($value);
    }

    public function getRequestExampleAttr($value)
    {
        return json_decode($value, true);
    }

    public function setErrorCodeAttr($value)
    {
        return json_encode($value);
    }

    public function getErrorCodeAttr($value)
    {
        return json_decode($value, true);
    }


    public function setResponseExampleAttr($value)
    {
        return json_encode($value);
    }

    public function getResponseExampleAttr($value)
    {
        return json_decode($value, true);
    }
}
