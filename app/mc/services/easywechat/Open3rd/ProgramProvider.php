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
namespace app\mc\services\easywechat\Open3rd;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * 注册第三方平台
 * Class ProgramProvider
 * @package app\mc\utils
 */
class ProgramProvider implements ServiceProviderInterface
{
    public function register(Container $pimple)
    {
        $pimple['mini_program.component_access_token'] = function ($pimple) {
            return new AccessToken(
                $pimple['config']['open3rd']['component_appid'],
                $pimple['config']['open3rd']['component_appsecret'],
                $pimple['config']['open3rd']['component_verify_ticket'],
                $pimple['config']['open3rd']['authorizer_appid']
            );
        };

        $pimple['mini_program.open3rd'] = function ($pimple) {
            return new ProgramOpen3rd($pimple['mini_program.component_access_token']);
        };
    }
}
