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

namespace app\mc\services\pay;


use app\mc\basic\BaseManager;
use app\mc\services\pay\storage\AliPay;
use app\mc\services\pay\storage\V3WechatPay;
use app\mc\services\pay\storage\WechatPay;
use think\facade\Config;

/**
 * 第三方支付
 * Class AllinPay
 * @package app\mc\services\pay
 * @mixin WechatPay
 */
class Pay extends BaseManager
{
    /**
     * 空间名
     * @var string
     */
    protected $namespace = '\\app\\mc\\services\\pay\\storage\\';

    /**
     * 默认驱动
     * @return mixed
     */
    protected function getDefaultDriver()
    {
        return Config::get('pay.default', 'wechat_pay');
    }

}
