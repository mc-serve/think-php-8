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

namespace app\system\services;


use app\system\dao\SystemCrudDataDao;
use app\mc\basic\BaseServices;

/**
 * Class SystemCrudDataService
 * @author 等风来
 * @email 136327134@qq.com
 * @date 2023/7/28
 * @package app\system\services
 */
class SystemCrudDataService extends BaseServices
{

    /**
     * SystemCrudDataService constructor.
     * @param SystemCrudDataDao $dao
     */
    public function __construct(SystemCrudDataDao $dao)
    {
        $this->dao = $dao;
    }

    /**
     * 获取全部数据
     * @return array
     * @throws \ReflectionException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author 等风来
     * @email 136327134@qq.com
     * @date 2023/8/1
     */
    public function getlistAll(string $name = '')
    {
        [$page, $limit] = $this->getPageValue();
        $list = $this->dao->selectList(['name' => $name], '*', $page, $limit, '', [], true)->toArray();
        $count = $this->dao->count(['name' => $name]);
        if ($page && $limit) {
            return compact('list', 'count');
        } else {
            return $list;
        }
    }
}
