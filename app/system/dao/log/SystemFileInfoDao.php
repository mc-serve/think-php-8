<?php

namespace app\system\dao\log;

use app\mc\basic\BaseDao;
use app\system\model\log\SystemFileInfo;

/**
 * @author 扫地僧
 * @email cto@mc-serve.com
 * @date 2023/04/07
 */
class SystemFileInfoDao extends BaseDao
{
    /**
     * 设置模型
     * @return string
     */
    protected function setModel(): string
    {
        return SystemFileInfo::class;
    }
}