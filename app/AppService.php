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

declare (strict_types = 1);

namespace app;

use think\Service;
use app\mc\services\SystemConfigService;
use app\mc\services\GroupDataService;
use app\mc\utils\Json;

/**
 * 应用服务类
 */
class AppService extends Service
{
    public function register()
    {
        // 服务注册
        $this->app->bind('json', Json::class);
        $this->app->bind('sysConfig', SystemConfigService::class);
        $this->app->bind('sysGroupData', GroupDataService::class);
    }

    public function boot()
    {
        // 服务启动
    }
}
