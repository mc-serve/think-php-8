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

declare (strict_types=1);

namespace app\system\dao;

use app\mc\basic\BaseDao;
use app\system\model\AppVersion;

/**
 * Class AppVersionDao
 * @package app\system\dao
 */
class AppVersionDao extends BaseDao
{
    /**
     * 设置模型
     * @return string
     */
    protected function setModel(): string
    {
        return AppVersion::class;
    }

    /**
     * 版本列表
     * @param $platform
     * @param $page
     * @param $limit
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function versionList($platform, $page, $limit)
    {
        return $this->getModel()->when($platform != '', function ($query) use ($platform) {
            $query->where('platform', $platform);
        })->when($page && $limit, function ($query) use ($page, $limit) {
            $query->page($page, $limit);
        })->order('add_time','desc')->select()->toArray();
    }
}
