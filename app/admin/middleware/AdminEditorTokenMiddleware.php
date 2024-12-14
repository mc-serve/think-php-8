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

namespace app\admin\middleware;


use app\Request;
use app\system\services\admin\AdminAuthServices;
use app\system\services\log\SystemFileServices;
use app\mc\exceptions\AuthException;
use app\mc\interfaces\MiddlewareInterface;
use app\mc\services\CacheService;
use think\facade\Config;

/**
 * 后台登陆验证中间件
 * Class AdminEditorTokenMiddleware
 * @package app\admin\middleware
 */
class AdminEditorTokenMiddleware implements MiddlewareInterface
{
    public function handle(Request $request, \Closure $next)
    {
        $token = CacheService::get(trim($request->get('fileToken')));

        /** @var SystemFileServices $service */
        $service = app()->make(SystemFileServices::class);
        $service->parseToken($token);

        return $next($request);
    }
}
