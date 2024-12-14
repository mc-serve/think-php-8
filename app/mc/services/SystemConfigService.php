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

namespace app\mc\services;

use app\system\services\config\SystemConfigServices;
use app\mc\utils\Arr;

/** 获取系统配置服务类
 * Class SystemConfigService
 * @package service
 */
class SystemConfigService
{
    const CACHE_SYSTEM = 'system_config';

    /**
     * 获取单个配置效率更高
     * @param string $key
     * @param $default
     * @param bool $isCaChe 是否获取缓存配置
     * @return bool|mixed|string
     */
    public static function get(string $key, $default = '', bool $isCaChe = false)
    {
        /** @var SystemConfigServices $service */
        $service = app()->make(SystemConfigServices::class);

        $callable = function () use ($service, $key) {
            return $service->getConfigValue($key);
        };

        try {
            return $callable();
        } catch (\Throwable $e) {dump($e);exit();
            return $default;
        }
    }

    /**
     * 获取多个配置
     * @param array $keys 示例 [['appid','1'],'appkey']
     * @param bool $isCaChe 是否获取缓存配置
     * @return array
     */
    public static function more(array $keys, bool $isCaChe = false)
    {
        /** @var SystemConfigServices $service */
        $service = app()->make(SystemConfigServices::class);

        $callable = function () use ($service, $keys) {
            return Arr::getDefaultValue($keys, $service->getConfigAll($keys));
        };
        try {
            return $callable();
        } catch (\Throwable $e) {
            return Arr::getDefaultValue($keys);
        }
    }

}
