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

namespace app\system\dao;


use app\mc\basic\BaseDao;
use app\system\model\SystemCrudData;

/**
 * Class SystemCrudDataDao
 * @author 等风来
 * @email 136327134@qq.com
 * @date 2023/7/28
 * @package app\system\dao
 */
class SystemCrudDataDao extends BaseDao
{

    /**
     * @return string
     * @author 等风来
     * @email 136327134@qq.com
     * @date 2023/7/28
     */
    protected function setModel(): string
    {
        return SystemCrudData::class;
    }
}
