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
use app\mc\services\oauth\OAuthInterface;

/**
 * 头条小程序登录
 * Class TouTiao
 * @package app\mc\services\oauth\storage
 */
class TouTiao extends BaseStorage implements OAuthInterface
{

    protected function initialize(array $config)
    {
        // TODO: Implement initialize() method.
    }

    public function getUserInfo(string $openid)
    {
        // TODO: Implement getUserInfo() method.
    }

    public function oauth(string $code = null, array $options = [])
    {
        // TODO: Implement oauth() method.
    }
}
