<?php

namespace app\system\dao\lang;

use app\mc\basic\BaseDao;
use app\system\model\lang\LangCountry;

class LangCountryDao extends BaseDao
{
    /**
     * 设置模型
     * @return string
     */
    protected function setModel(): string
    {
        return LangCountry::class;
    }
}