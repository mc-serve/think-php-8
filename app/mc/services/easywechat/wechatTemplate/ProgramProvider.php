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
namespace app\mc\services\easywechat\wechatTemplate;

use EasyWeChat\Core\AccessToken;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class ProgramProvider implements ServiceProviderInterface
{
    public function register(Container $pimple)
    {
        $pimple['wechat.access_token'] = function ($pimple) {
            return new AccessToken(
                $pimple['config']['app_id'],
                $pimple['config']['secret'],
                $pimple['cache']
            );
        };

        $pimple['new_notice'] = function ($pimple) {
            return new ProgramTemplate($pimple['wechat.access_token']);
        };
    }
}