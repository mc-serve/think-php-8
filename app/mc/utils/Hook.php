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
namespace app\mc\utils;

use think\helper\Str;

/**
 *
 * Class Hook
 * @package app\mc\utils
 */
class Hook
{
    /**
     * 类名
     * @var string
     */
    protected $namespace;

    /**
     * 方法前缀
     * @var string
     */
    protected $prefix;

    /**
     * 构造方法
     * Hook constructor.
     * @param string $namespace
     * @param string|null $prefix
     */
    public function __construct(string $namespace, string $prefix = null)
    {
        $this->namespace = $namespace;
        if ($prefix) {
            $this->prefix = $prefix;
        }
    }

    /**
     * 执行挂载方法
     * @param string $hookName
     * @param mixed ...$arguments
     * @return bool
     */
    public function listen(string $hookName, ...$arguments)
    {
        if (class_exists($this->namespace)) {
            $handle = app()->make($this->namespace);
            $hookName = Str::studly(($this->prefix ?: '') . ucfirst($hookName));
            if (method_exists($handle, $hookName)) {
                try {
                    return call_user_func_array([$handle, $hookName], $arguments);
                } catch (\Throwable $e) {
                }
            }
        }
        return false;
    }
}
