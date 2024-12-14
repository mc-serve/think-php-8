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

namespace app\mc\services\printer;

use app\mc\basic\BaseManager;
use think\facade\Config;
use think\Container;

/**
 * Class Printer
 * @package app\mc\services\auth
 * @mixin \app\mc\services\printer\storage\YiLianYun
 */
class Printer extends BaseManager
{

    /**
     * 空间名
     * @var string
     */
    protected $namespace = '\\app\\mc\\services\\printer\\storage\\';

    /**
     * @var object
     */
    protected $handleAccessToken;

    /**
     * 默认驱动
     * @return mixed
     */
    protected function getDefaultDriver()
    {
        return Config::get('printer.default', 'yi_lian_yun');
    }

    /**
     * 获取类的实例
     * @param $class
     * @return mixed|void
     */
    protected function invokeClass($class)
    {
        if (!class_exists($class)) {
            throw new \RuntimeException('class not exists: ' . $class);
        }
        $this->getConfigFile();

        if (!$this->config) {
            $this->config = Config::get($this->configFile . '.stores.' . $this->name, []);
        }

        if (!$this->handleAccessToken) {
            $this->handleAccessToken = new AccessToken($this->config, $this->name, $this->configFile);
        }

        $handle = Container::getInstance()->invokeClass($class, [$this->name, $this->handleAccessToken, $this->configFile]);
        $this->config = [];
        return $handle;
    }

}
