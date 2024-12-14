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
 * 逻辑层方法枚举
 * Class ServiceActionEnum
 * @author 等风来
 * @email 136327134@qq.com
 * @date 2023/8/14
 * @package app\mc\services\crud\enum
 */
class ServiceActionEnum
{
    //搜索
    const INDEX = 'index';
    //获取表单
    const FORM = 'form';
    //保存
    const SAVE = 'save';
    //更新
    const UPDATE = 'update';

    const SERVICE_ACTION_ALL = [
        self::INDEX,
        self::FORM,
        self::SAVE,
        self::UPDATE
    ];
}
