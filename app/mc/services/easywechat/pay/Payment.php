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

namespace app\mc\services\easywechat\pay;

use app\mc\exceptions\PayException;
use EasyWeChat\Pay\Message;
use think\facade\Event;
use app\services\pay\PayServices;

class Payment
{
    public $app = null;
    public $config = null;

    public function __construct($app, $config)
    {
        $this->app = $app;
        $this->config = $config;
    }

    public function prepare($order) {
        $api = $this->app->getClient();
        $response = $api->postXml('/pay/unifiedorder', [
            'appid' => $this->config['app_id'],
            'mch_id' => $this->config['merchant_id'],
            'nonce_str' => $this->generateRandomString(),
            // 'sign' => '202203081646729819743',
            'body' => $order->data['body'],
            'out_trade_no' => $order->data['out_trade_no'],
            'total_fee' => $order->data['total_fee'],
            'spbill_create_ip' => '0.0.0.0',
            'notify_url' => $this->config['notify_url'],
            'trade_type' => $order->data['trade_type'],
            'attach' => $order->data['attach'],
            'openid' => $order->data['openid'],
         ]);
         dump($response->getContent());
        exit();
    }

    public function jsapiPay($openid, $orderId, $totalFee, $body, $attach) {
        $app_id = $this->app->getConfig()['app_id'];
        $response = $this->app->getClient()->postJson("v3/pay/transactions/jsapi", [
            "mchid" => $this->config['mchid'], // <---- 请修改为您的商户号
            "out_trade_no" => $orderId,
            "appid" => $app_id, // <---- 请修改为服务号的 appid
            "description" => $body,
            "notify_url" => $this->config['notify_url'],
            "amount" => [
                "total" => (int)bcmul($totalFee, 100),
                "currency" => "CNY"
            ],
            "payer" => [
                "openid" => $openid // <---- 请修改为服务号下单用户的 openid
            ]
        ]);
        if ($response->isFailed()) {
            throw new PayException('微信支付:下单失败');
        }
        if (!$response->isSuccessful()) {
            throw new PayException('微信支付:下单失败2');
        }
        $utils = $this->app->getUtils();
        $signType = 'RSA'; // 默认RSA，v2要传MD5
        return $utils->buildSdkConfig($response['prepay_id'], $app_id, $signType);
    }

    public function h5Pay($orderId, $totalFee, $body, $attach) {
        $app_id = $this->app->getConfig()['app_id'];
        $response = $this->app->getClient()->postJson("v3/pay/transactions/h5", [
            "mchid" => $this->config['mchid'], // <---- 请修改为您的商户号
            "out_trade_no" => $orderId,
            "appid" => $app_id, // <---- 请修改为服务号的 appid
            "description" => $body,
            "notify_url" => $this->config['notify_url'],
            "amount" => [
                "total" => (int)bcmul($totalFee, 100),
                "currency" => "CNY"
            ],
            // "payer" => [
            //     "openid" => $openid // <---- 请修改为服务号下单用户的 openid
            // ],
            "scene_info" => [
                "payer_client_ip" => request()->ip(),
                // "device_id" => "001",
                // "store_info" => [
                //     "id" => "0001",
                //     "name" => "紫衫湖摄影中心",
                //     "area_code" => "440605",
                //     "address" => "广东省佛山市南海区恒大御景紫衫湖摄影中心"
                // ],
                "h5_info" => [
                    "type" => 'Wap',
                    // "app_name" => "融易购",
                    // "app_url" => "http://r-yigou.cn",
                    // "bundle_id" => "com.tencent.wzryiOS",
                    // "package_name" => "com.tencent.tmgp.sgame"
                ]
            ],
        ]);
        if ($response->isFailed()) {
            throw new PayException('微信支付:下单失败');
        }
        if (!$response->isSuccessful()) {
            throw new PayException('微信支付:下单失败2');
        }
        $res = json_decode($response->getContent(), true);
        return $res;
    }

    public function generateRandomString($length = 32) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
    
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
    
        return $randomString;
    }

    /**
     * 支付异步回调
     * @return string
     */
    public function handleNotify()
    {
        try {
            return $this->notify(function ($notify) {
            
                if (isset($notify->out_trade_no)) {
    
                    $data = [
                        'attach' => $notify->attach,
                        'out_trade_no' => $notify->out_trade_no,
                        'transaction_id' => $notify->trade_no
                    ];
                    \think\facade\Log::write($data);
    
                    return Event::until('NotifyListener', [$data, PayServices::WEIXIN_PAY]);
                }
                return false;
            });
        } catch (\Exception $e) {
            
        }
    }

    /**
     * 异步回调
     * @param callable $notifyFn
     * @return string
     */
    public function notify(callable $notifyFn)
    {
        app()->request->filter(['trim']);
        $paramInfo = app()->request->postMore([
            ['gmt_create', ''],
            ['charset', ''],
            ['seller_email', ''],
            ['subject', ''],
            ['sign', ''],
            ['buyer_id', ''],
            ['invoice_amount', ''],
            ['notify_id', ''],
            ['fund_bill_list', ''],
            ['notify_type', ''],
            ['trade_status', ''],
            ['receipt_amount', ''],
            ['buyer_pay_amount', ''],
            ['app_id', ''],
            ['seller_id', ''],
            ['sign_type', ''],
            ['gmt_payment', ''],
            ['notify_time', ''],
            ['passback_params', ''],
            ['version', ''],
            ['out_trade_no', ''],
            ['total_amount', ''],
            ['trade_no', ''],
            ['auth_app_id', ''],
            ['buyer_logon_id', ''],
            ['point_amount', ''],
        ], false, false);
        $server = $this->app->getServer();
        $server->handlePaid(function (Message $message, \Closure $next) use ($notifyFn) {
            // \think\facade\Log::write($message->out_trade_no);
            //商户订单号
            $postOrder['out_trade_no'] = $message->out_trade_no ?? '';
            //微信交易号
            $postOrder['trade_no'] = $message->transaction_id ?? '';
            //交易状态
            $postOrder['trade_status'] = $message->trade_state ?? '';
            $postOrder['openid'] = $message->payer['openid'];
            //备注
            $postOrder['attach'] = isset($message->attach) ? urldecode($message->attach) : 'wechat';
            try {
                if ($notifyFn((object)$postOrder)) {
                    return 'success';
                }
            } catch (\Exception $e) {
                dump($e->getMessage());exit();
                \think\facade\Log::error($e->getMessage());
                \think\facade\Log::error('微信异步会回调成功,执行函数错误。错误单号：' . $postOrder['out_trade_no']);
            }
            // $message->out_trade_no 获取商户订单号
            // $message->payer['openid'] 获取支付者 openid
            // 🚨🚨🚨 注意：推送信息不一定靠谱哈，请务必验证
            // 建议是拿订单号调用微信支付查询接口，以查询到的订单状态为准
            // return $next($message);
        });

        $res = $server->serve();
        return $this;
    }

    public function getContent()
    {
        // return 'fail';
        return 'success';
    }
}