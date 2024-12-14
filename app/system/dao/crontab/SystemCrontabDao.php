<?php

namespace app\system\dao\crontab;

use app\mc\basic\BaseDao;
use app\system\model\crontab\SystemCrontab;

class SystemCrontabDao extends BaseDao
{
    /**
     * 设置模型
     * @return string
     */
    protected function setModel(): string
    {
        return SystemCrontab::class;
    }
}