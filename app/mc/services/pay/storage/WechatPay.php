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

namespace app\mc\services\pay\storage;


use app\mc\exceptions\AdminException;
use app\mc\services\pay\BasePay;
use app\mc\exceptions\PayException;
use app\mc\services\pay\PayInterface;
use app\mc\services\app\MiniProgramService;
use app\mc\services\app\WechatService;
use app\mc\services\SystemConfigService;
use EasyWeChat\Payment\API;
use EasyWeChat\Payment\Order;
use EasyWeChat\Support\Collection;
use Psr\Http\Message\ResponseInterface;

/**
 * 微信支付
 * Class WechatPay
 * @package app\mc\services\pay\storage
 */
class WechatPay extends BasePay implements PayInterface
{

    protected function initialize(array $config)
    {
        // TODO: Implement initialize() method.
    }

    /**
     * 创建订单进行支付
     * @param string $orderId
     * @param string $totalFee
     * @param string $attach
     * @param string $body
     * @param string $detail
     * @param array $options
     * @return array|mixed|string
     */
    public function create(string $orderId, string $totalFee, string $attach, string $body, string $detail, array $options = [])
    {
        $this->authSetPayType();

        switch ($this->payType) {
            // case Order::NATIVE:
            case 'NATIVE':
                return WechatService::nativePay(null, $orderId, $totalFee, $attach, $body, $detail);
            // case Order::APP:
            case 'APP':
                return WechatService::appPay($options['openid'], $orderId, $totalFee, $attach, $body, $detail);
            // case Order::JSAPI:
            case 'jsapi':
                if (empty($options['openid'])) {
                    throw new PayException('缺少openid');
                }
                if (request()->isRoutine()) {
                    // 获取配置  判断是否为新支付
                    if ($options['pay_new_weixin_open']) {
                        return MiniProgramService::newJsPay($options['openid'], $orderId, $totalFee, $attach, $body, $detail, $options);
                    }
                    return MiniProgramService::jsPay($options['openid'], $orderId, $totalFee, $attach, $body, $detail);
                }
                return WechatService::jsPay($options['openid'], $orderId, $totalFee, $attach, $body, $detail);
            case 'h5':
                return WechatService::paymentPrepare(null, $orderId, $totalFee, $attach, $body, $detail, 'MWEB');
            default:
                throw new PayException('微信支付:支付类型错误');
        }
    }

    /**
     * 支付到零钱
     * @param string $openid
     * @param string $orderId
     * @param string $amount
     * @param array $options
     * @return bool|mixed
     */
    public function merchantPay(string $openid, string $orderId, string $amount, array $options = [])
    {
        return WechatService::merchantPay($openid, $orderId, $amount, $options['desc'] ?? '');
    }

    /**
     * 退款
     * @param string $outTradeNo
     * @param array $opt
     * @return Collection|mixed|ResponseInterface
     */
    public function refund(string $outTradeNo, array $opt = [])
    {
        if (!isset($opt['pay_price'])) throw new PayException(400730);
        $totalFee = floatval(bcmul($opt['pay_price'], 100, 0));
        $refundFee = isset($opt['refund_price']) ? floatval(bcmul($opt['refund_price'], 100, 0)) : null;
        $refundReason = $opt['desc'] ?? '';
        $refundNo = $opt['refund_id'] ?? $outTradeNo;
        $opUserId = $opt['op_user_id'] ?? null;
        $type = $opt['type'] ?? 'out_trade_no';
        /**
         * 仅针对老资金流商户使用
         * REFUND_SOURCE_UNSETTLED_FUNDS---未结算资金退款（默认使用未结算资金退款）
         * REFUND_SOURCE_RECHARGE_FUNDS---可用余额退款
         */
        $refundAccount = $opt['refund_account'] ?? 'REFUND_SOURCE_UNSETTLED_FUNDS';
        if (isset($opt['wechat'])) {
            $result = WechatService::refund($outTradeNo, $refundNo, $totalFee, $refundFee, $opUserId, $refundReason, $type, $refundAccount);
        } else {
            if ($opt['pay_new_weixin_open']) {
                $result = MiniProgramService::miniRefund($outTradeNo, $totalFee, $refundFee, $opt);
            } else {
                $result = MiniProgramService::refund($outTradeNo, $refundNo, $totalFee, $refundFee, $opUserId, $refundReason, $type, $refundAccount);
            }
        }
        if (!empty($opt['pay_new_weixin_open'])) {
            if ($result['errcode'] != 0) throw new AdminException($result['errmsg']);
        } else {
            if (isset($result['return_code']) && $result['return_code'] != 'SUCCESS') throw new AdminException($result['return_msg']);
            if (isset($result['result_code']) && $result['result_code'] != 'SUCCESS') throw new AdminException($result['err_code_des']);
            if (isset($result['status']) && $result['status'] != 'SUCCESS') throw new AdminException($result['status']);
        }
    }

    /**
     * 查询退款订单
     * @param string $outTradeNo
     * @param string $outRequestNo
     * @param array $other
     * @return Collection|mixed|ResponseInterface
     */
    public function queryRefund(string $outTradeNo, string $outRequestNo, array $other = [])
    {
        return WechatService::queryRefund($outTradeNo, $other['type'] ?? API::OUT_TRADE_NO);
    }

    /**
     * 异步回调
     * @return mixed|\Symfony\Component\HttpFoundation\Response
     * @throws \EasyWeChat\Core\Exceptions\FaultException
     */
    public function handleNotify()
    {
        return WechatService::handleNotify();
    }
}
