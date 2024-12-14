<?php

namespace app\system\dao;

use app\mc\basic\BaseDao;
use app\system\model\SystemSignReward;

/**
 * @author: 扫地僧
 * @email: cto@mc-serve.com
 * @date: 2023/7/28
 */
class SystemSignRewardDao extends BaseDao
{
    /**
     * 设置模型
     * @return string
     * @author: 扫地僧
     * @email: cto@mc-serve.com
     * @date: 2023/7/28
     */
    protected function setModel(): string
    {
        return SystemSignReward::class;
    }
}