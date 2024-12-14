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

namespace app\mc\services\template;

use app\mc\basic\BaseManager;
use think\facade\Config;

/**
 * Class Template
 * @package app\mc\services\template
 * @mixin \app\mc\services\template\storage\Wechat
 * @mixin \app\mc\services\template\storage\Subscribe
 */
class Template extends BaseManager
{

    /**
     * 空间名
     * @var string
     */
    protected $namespace = '\\app\\mc\\services\\template\\storage\\';

    /**
     * 设置默认扩展
     * @return mixed|string
     */
    protected function getDefaultDriver()
    {
        return 'wechat';
    }
}
