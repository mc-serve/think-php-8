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

namespace app\system\services\admin;


use app\system\dao\admin\AdminAuthDao;
use app\mc\basic\BaseServices;
use app\services\other\CacheServices;
use app\mc\exceptions\AuthException;
use app\mc\services\CacheService;
use app\mc\utils\JwtAuth;
use Firebase\JWT\ExpiredException;

/**
 * admin授权service
 * Class AdminAuthServices
 * @package app\system\services\admin
 */
class AdminAuthServices extends BaseServices
{
    /**
     * 构造方法
     * AdminAuthServices constructor.
     * @param AdminAuthDao $dao
     */
    public function __construct(AdminAuthDao $dao)
    {
        $this->dao = $dao;
    }

    /**
     * 获取Admin授权信息
     * @param string $token
     * @param int $code
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function parseToken(string $token, int $code = 110003): array
    {
        /** @var CacheService $cacheService */
        $cacheService = app()->make(CacheService::class);

        if (!$token || $token === 'undefined') {
            throw new AuthException($code);
        }
        /** @var JwtAuth $jwtAuth */
        $jwtAuth = app()->make(JwtAuth::class);
        //设置解析token
        [$id, $type, $pwd] = $jwtAuth->parseToken($token);

        //检测token是否过期
        $md5Token = md5($token);
        if (!$cacheService->has($md5Token) || !$cacheService->get($md5Token, '', NULL, 'admin')) {
            $this->authFailAfter($id, $type);
            throw new AuthException($code);
        }

        //验证token
        try {
            $jwtAuth->verifyToken();
        } catch (\Throwable $e) {
            if (!request()->isCli()) {
                $cacheService->delete($md5Token);
            }
            $this->authFailAfter($id, $type);
            throw new AuthException($code);
        }

        //获取管理员信息
        $adminInfo = $this->dao->get($id);
        if (!$adminInfo || !$adminInfo->id) {
            if (!request()->isCli()) {
                $cacheService->delete($md5Token);
            }
            $this->authFailAfter($id, $type);
            throw new AuthException($code);
        }
        if ($pwd !== '' && $pwd !== md5($adminInfo->pwd)) {
            throw new AuthException($code);
        }

        $adminInfo->type = $type;
        return $adminInfo->hidden(['pwd', 'is_del', 'status'])->toArray();
    }

    /**
     * token验证失败后事件
     */
    protected function authFailAfter($id, $type)
    {
        try {
            $postData = request()->post();
            $rule = trim(strtolower(request()->rule()->getRule()));
            $method = trim(strtolower(request()->method()));
            //添加商品退出后事件
            if ($rule === 'product/product/<id>' && $method === 'post') {
                $this->saveProduct($id, $postData);
            }
        } catch (\Throwable $e) {
        }
    }

    /**
     * 保存提交数据
     * @param $adminId
     * @param $postData
     */
    protected function saveProduct($adminId, $postData)
    {
        /** @var CacheServices $cacheService */
        $cacheService = app()->make(CacheServices::class);
        $cacheService->setDbCache($adminId . '_product_data', $postData, 68400);
    }
}
