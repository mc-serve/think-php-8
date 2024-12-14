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

class SystemRouteCate extends BaseModel
{

    /**
     * @var string
     */
    protected $name = 'system_route_cate';

    /**
     * @var string
     */
    protected $pk = 'id';

    protected $autoWriteTimestamp = false;

    /**
     * @return \think\model\relation\HasMany
     * @author 等风来
     * @email 136327134@qq.com
     * @date 2023/4/7
     */
    public function children()
    {
        return $this->hasMany(SystemRoute::class, 'store_category_id', 'id')->field(['id', 'type', 'store_category_id', 'name', 'name as real_name', 'path', 'method'])->order('add_time desc');
    }
}
