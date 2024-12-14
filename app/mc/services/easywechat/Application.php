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

namespace app\mc\services\easywechat;


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
use app\mc\services\easywechat\officialAccount\Oauth2;


/**
 * Class Application
 * @package app\mc\services\easywechat
 * @property LiveProgramProvider $wechat_live
 * @property WechatOauth2Provider $oauth2
 * @property PayClient $v3pay
 * @property OrderClient $order_ship
 */
class Application extends \EasyWeChat\OfficialAccount\Application
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
    public $oauth2 = null;

    /**
     * Application constructor.
     * @param $config
     */
    public function __construct($config)
    {
        // dump($this->providers);
        // dump($this->providersNew);
        // dump($config);
        // exit();
        $configNew = [
            'app_id' => $config['app_id'],
            'secret' => $config['secret'],
            'token' => $config['token'],
            'aes_key' => '', // 明文模式请勿填写 EncodingAESKey
        
            /**
             * OAuth 配置
             *
             * scopes：公众平台（snsapi_userinfo / snsapi_base），开放平台：snsapi_login
             * callback：OAuth授权完成后的回调页地址
             */
            'oauth' => [
                'scopes'   => ['snsapi_userinfo'],
                'callback' => '/examples/oauth_callback.php',
            ],
        
            /**
             * 接口请求相关配置，超时时间等，具体可用参数请参考：
             * https://github.com/symfony/symfony/blob/5.3/src/Symfony/Contracts/HttpClient/HttpClientInterface.php
             */
            'http' => [
                'timeout' => 5.0,
                // 'base_uri' => 'https://api.weixin.qq.com/', // 如果你在国外想要覆盖默认的 url 的时候才使用，根据不同的模块配置不同的 uri
        
                'retry' => true, // 使用默认重试配置
                //  'retry' => [
                //      // 仅以下状态码重试
                //      'status_codes' => [429, 500]
                //       // 最大重试次数
                //      'max_retries' => 3,
                //      // 请求间隔 (毫秒)
                //      'delay' => 1000,
                //      // 如果设置，每次重试的等待时间都会增加这个系数
                //      // (例如. 首次:1000ms; 第二次: 3 * 1000ms; etc.)
                //      'multiplier' => 3
                //  ],
            ],
        ];
        // $this->providers = array_merge($this->providers, $this->providersNew);
        parent::__construct($configNew);
        $this->qrcode = new Qrcode($this);
        $this->js = new JsSdk($this);
        $this->oauth2 = new Oauth2($this);
        if (!empty($config['payment']))
            $this->payment = new Payment($this, $config['payment']);
    }

    public function getMyToken() {
        return CacheService::remember('officialAccount_access_token', function () {
            $accessToken = $this->getAccessToken();
            return $accessToken->getToken();
        }, (60 * 60) - 10);
    }
}
