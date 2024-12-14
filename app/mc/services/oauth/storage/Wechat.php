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

namespace app\mc\services\oauth\storage;


use app\mc\basic\BaseStorage;
use app\mc\services\app\WechatOpenService;
use app\mc\services\app\WechatService;
use app\mc\services\oauth\OAuthException;
use app\mc\services\oauth\OAuthInterface;

/**
 * 微信公众号登录
 * Class Wechat
 * @package app\mc\services\oauth\storage
 */
class Wechat extends BaseStorage implements OAuthInterface
{

    protected function initialize(array $config)
    {
        // TODO: Implement initialize() method.
    }


    /**
     * 获取用户信息
     * @param string $openid
     * @return mixed
     */
    public function getUserInfo(string $openid)
    {
        return WechatService::oauth2Service()->getUserInfo($openid)->toArray();
    }

    /**
     * 授权
     * @param string|null $code
     * @param array $options
     * @return \EasyWeChat\Support\Collection|mixed
     */
    public function oauth(string $code = null, array $options = [])
    {
        $open = false;
        if (!empty($options['open'])) {
            $open = true;
        }

        if (!$open) {
            try {
                $wechatInfo = WechatService::oauth2Service()->oauth();
            } catch (\Throwable $e) {
                throw new OAuthException($e->getMessage());
            }
        } else {
            /** @var WechatOpenService $service */
            $service = app()->make(WechatOpenService::class);
            $wechatInfo = $service->getAuthorizationInfo();
            if (!$wechatInfo) {
                throw new OAuthException(410131);
            }
        }

        return $wechatInfo;
    }
}
