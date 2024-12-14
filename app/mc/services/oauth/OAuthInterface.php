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

namespace app\mc\services\oauth;

/**
 * 第三方登录
 * Interface OAuthInterface
 * @package app\mc\services\oauth
 */
interface OAuthInterface
{

    /**
     * 获取用户信息
     * @param string $openid
     * @return mixed
     */
    public function getUserInfo(string $openid);

    /**
     * 授权
     * @param string|null $code
     * @param array $options
     * @return mixed
     */
    public function oauth(string $code = null, array $options = []);

}
