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


use app\mc\basic\BaseManager;
use app\mc\services\oauth\storage\MiniProgram;
use app\mc\services\oauth\storage\TouTiao;
use app\mc\services\oauth\storage\Wechat;
use think\facade\Config;

/**
 * 第三方登录
 * Class OAuth
 * @package app\mc\services\oauth
 * @mixin Wechat
 * @mixin TouTiao
 * @mixin MiniProgram
 */
class OAuth extends BaseManager
{

    /**
     * 空间名
     * @var string
     */
    protected $namespace = '\\app\\mc\\services\\oauth\\storage\\';

    /**
     * @return mixed
     */
    protected function getDefaultDriver()
    {
        return Config::get('oauth.default', 'wechat');
    }
}
