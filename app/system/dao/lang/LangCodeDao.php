<?php

namespace app\system\dao\lang;

use app\mc\basic\BaseDao;
use app\system\model\lang\LangCode;

class LangCodeDao extends BaseDao
{
    /**
     * 设置模型
     * @return string
     */
    protected function setModel(): string
    {
        return LangCode::class;
    }
}