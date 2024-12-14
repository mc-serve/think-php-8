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
 * 表单类型枚举
 * Class FormTypeEnum
 * @author 等风来
 * @email 136327134@qq.com
 * @date 2023/8/14
 * @package app\mc\services\crud\enum
 */
class FormTypeEnum
{
    //下拉框
    const SELECT = 'select';
    //输入框
    const INPUT = 'input';
    //数字输入框
    const NUMBER = 'number';
    //多行文本框
    const TEXTAREA = 'textarea';
    //单选日期时间
    const DATE_TIME = 'dateTime';
    //日期时间区间选择
    const DATE_TIME_RANGE = 'dateTimeRange';
    //多选框
    const  CHECKBOX = 'checkbox';
    //开关
    const SWITCH = 'switches';
    //单选框
    const  RADIO = 'radio';
    //单图选择
    const FRAME_IMAGE_ONE = 'frameImageOne';
    //多图选择
    const  FRAME_IMAGES = 'frameImages';

    const FORM_TYPE_ALL = [
        self::INPUT,
        self::NUMBER,
        self::RADIO,
        self::SELECT,
        self::TEXTAREA,
        self::FRAME_IMAGE_ONE,
        self::FRAME_IMAGES,
        self::CHECKBOX,
        self::DATE_TIME,
        self::DATE_TIME_RANGE,
    ];


}
