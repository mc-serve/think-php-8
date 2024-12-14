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

namespace app\mc\services\crud\enum;

/**
 * 搜索方式枚举
 * Class SearchEnum
 * @author 等风来
 * @email 136327134@qq.com
 * @date 2023/8/14
 * @package app\mc\services\crud\enum
 */
class SearchEnum
{
    //等于
    const SEARCH_TYPE_EQ = '=';
    //小于等于
    const SEARCH_TYPE_LTEQ = '<=';
    //大于等于
    const SEARCH_TYPE_GTEQ = '>=';
    //不等于
    const SEARCH_TYPE_NEQ = '<>';
    //模糊搜索
    const SEARCH_TYPE_LIKE = 'LIKE';
    //区间-用来时间区间搜索
    const SEARCH_TYPE_BETWEEN = 'BETWEEN';

    //搜索类型
    const SEARCH_TYPE = [
        self::SEARCH_TYPE_EQ,
        self::SEARCH_TYPE_LTEQ,
        self::SEARCH_TYPE_GTEQ,
        self::SEARCH_TYPE_NEQ,
        self::SEARCH_TYPE_LIKE,
        self::SEARCH_TYPE_BETWEEN,
    ];
}
