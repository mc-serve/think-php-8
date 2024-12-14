<?php

namespace app\system\dao\lang;

use app\mc\basic\BaseDao;
use app\system\model\lang\LangType;

class LangTypeDao extends BaseDao
{
    /**
     * 设置模型
     * @return string
     */
    protected function setModel(): string
    {
        return LangType::class;
    }
}