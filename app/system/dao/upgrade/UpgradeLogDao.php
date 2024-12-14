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

namespace app\system\dao\upgrade;

use app\mc\basic\BaseDao;
use app\system\model\upgrade\UpgradeLog;

/**
 * 升级记录dao
 * Class UpgradeLogDao
 * @package app\system\dao\upgrade
 */
class UpgradeLogDao extends BaseDao
{

    protected function setModel(): string
    {
        return UpgradeLog::class;
    }

    /**
     * 列表
     * @param array $where
     * @param array $field
     * @param int $page
     * @param int $limit
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getList(array $field, int $page = 0, int $limit = 0): array
    {
        return $this->search()->field($field)->page($page, $limit)->select()->toArray();
    }
}