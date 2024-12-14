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

use EasyWeChat\Payment\Order;
use app\mc\basic\BaseStorage;

/**
 * Class BasePay
 * @package app\mc\services\pay
 */
abstract class BasePay extends BaseStorage
{
    /**
     * @var string
     */
    protected $payType;

    /**
     * 设置支付类型
     * @param string $type
     * @return $this
     */
    public function setPayType(string $type)
    {
        $this->payType = $type;
        return $this;
    }

    /**
     * 设置支付类型
     * @param string $type
     * @return $this
     */
    public function authSetPayType()
    {
        if (!$this->payType) {
            if (request()->isPc()) {
                $this->payType = Order::NATIVE;
            }
            if (request()->isApp()) {
                $this->payType = Order::APP;
            }
            if (request()->isRoutine() || request()->isWechat()) {
                $this->payType = 'jsapi';
            }
            if (request()->isH5()) {
                $this->payType = 'h5';
            }
        }
    }


}
