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


use app\mc\services\easywechat\miniPayment\ServiceProvider;
use app\mc\services\easywechat\oauth2\wechat\WechatOauth2Provider;
use app\mc\services\easywechat\orderShipping\OrderClient;
use app\mc\services\easywechat\subscribe\ProgramProvider;
use app\mc\services\easywechat\v3pay\PayClient;
use app\mc\services\easywechat\wechatlive\ProgramProvider as LiveProgramProvider;
use app\mc\services\easywechat\wechatTemplate\ProgramProvider as TemplateProvider;
use app\mc\services\easywechat\v3pay\ServiceProvider as V3PayServiceProvider;
use app\mc\services\easywechat\orderShipping\ServiceProvider as OrderServiceProvider;
use app\mc\services\CacheService;

use app\mc\services\easywechat\officialAccount\Qrcode;
use app\mc\services\easywechat\officialAccount\JsSdk;
use app\mc\services\easywechat\pay\Payment;


/**
 * Class Application
 * @package app\mc\services\easywechat
 * @property LiveProgramProvider $wechat_live
 * @property WechatOauth2Provider $oauth2
 * @property PayClient $v3pay
 * @property OrderClient $order_ship
 */
class Application extends \EasyWeChat\Pay\Application
{

    /**
     * @var string[]
     */
    protected $providersNew = [
        LiveProgramProvider::class,
        WechatOauth2Provider::class,
        ServiceProvider::class,
        ProgramProvider::class,
        V3PayServiceProvider::class,
        \app\mc\services\easywechat\Open3rd\ProgramProvider::class,
        OrderServiceProvider::class,
        TemplateProvider::class
    ];

    public $qrcode = null;
    public $js = null;
    public $payment = null;
    public $v3pay = null;

    /**
     * Application constructor.
     * @param $config
     */
    public function __construct($config)
    {
        // dump($this->providers);
        // dump($this->providersNew);
        $configNew = [
            'app_id' => $config['wechat']['appid'],
            'mch_id' => $config['v3_payment']['mchid'],
        
            // 商户证书
            'private_key' => $config['v3_payment']['key_path'],
            'certificate' => $config['v3_payment']['cert_path'],
        
            // v3 API 秘钥
            'secret_key' => $config['v3_payment']['key'],
        
            // v2 API 秘钥
            'v2_secret_key' => $config['v3_payment']['cert_path'],
        
            // 平台证书：微信支付 APIv3 平台证书，需要使用工具下载
            // 下载工具：https://github.com/wechatpay-apiv3/CertificateDownloader
            'platform_certs' => [
                // 请使用绝对路径
                // '/path/to/wechatpay/cert.pem',
            ],
        
            /**
             * 接口请求相关配置，超时时间等，具体可用参数请参考：
             * https://github.com/symfony/symfony/blob/5.3/src/Symfony/Contracts/HttpClient/HttpClientInterface.php
             */
            'http' => [
                'throw'  => false, // 状态码非 200、300 时是否抛出异常，默认为开启
                'timeout' => 5.0,
                // 'base_uri' => 'https://api.mch.weixin.qq.com/', // 如果你在国外想要覆盖默认的 url 的时候才使用，根据不同的模块配置不同的 uri
            ],
        ];
        // $this->providers = array_merge($this->providers, $this->providersNew);
        parent::__construct($configNew);
        $this->qrcode = new Qrcode($this);
        $this->js = new JsSdk($this);
        if (!empty($config['v3_payment']))
            $this->payment = new Payment($this, $config['v3_payment']);
            $this->v3pay = new Payment($this, $config['v3_payment']);
    }

    public function getMyToken() {
        return CacheService::remember('officialAccount_access_token', function () {
            $accessToken = $this->getAccessToken();
            return $accessToken->getToken();
        }, (60 * 60) - 10);
    }
}
