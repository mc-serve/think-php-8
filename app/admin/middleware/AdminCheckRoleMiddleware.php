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
use app\system\services\admin\SystemRoleServices;
use app\mc\exceptions\AuthException;
use app\mc\interfaces\MiddlewareInterface;

/**
 * 权限规则验证
 * Class AdminCheckRoleMiddleware
 * @package app\http\middleware
 */
class AdminCheckRoleMiddleware implements MiddlewareInterface
{
    /**
     * 权限规则验证
     * @param Request $request
     * @param \Closure $next
     * @return mixed
     * @throws \throwable
     */
    public function handle(Request $request, \Closure $next)
    {
        if (!$request->adminId() || !$request->adminInfo())
            throw new AuthException(100100);

        if ($request->adminInfo()['level']) {
            /** @var SystemRoleServices $systemRoleService */
            $systemRoleService = app()->make(SystemRoleServices::class);
            $systemRoleService->verifyAuth($request);
        }

        return $next($request);
    }
}
