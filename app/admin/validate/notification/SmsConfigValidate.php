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
namespace app\admin\validate\notification;

use think\Validate;

/**
 *
 * Class SmsConfigValidate
 * @package app\admin\validates
 */
class SmsConfigValidate extends Validate
{
    /**
     * 定义验证规则
     * @var array
     */
    protected $rule = [
        'sms_account' => ['require'],
        'sms_token' => ['require'],
    ];

    /**
     * 定义错误信息
     * @var array
     */
    protected $message = [
        'sms_account.require' => '400387',
        'sms_token.require' => '400388',
    ];
}
