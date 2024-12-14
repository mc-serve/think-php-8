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

namespace app\mc\services\oauth\storage;


use app\mc\basic\BaseStorage;
use app\mc\services\app\MiniProgramService;
use app\mc\services\oauth\OAuthException;
use app\mc\services\oauth\OAuthInterface;

/**
 * 小程序登录
 * Class MiniProgram
 * @package app\mc\services\oauth\storage
 */
class MiniProgram extends BaseStorage implements OAuthInterface
{

    protected function initialize(array $config)
    {
        // TODO: Implement initialize() method.
    }

    /**
     * @param string $openid
     * @return array|mixed
     */
    public function getUserInfo(string $openid)
    {
        return [];
    }

    /**
     * 授权登录
     * @param string|null $code
     * @param array $options
     * @return mixed
     */
    public function oauth(string $code = null, array $options = [])
    {
        if (!$code) {
            throw new OAuthException(100104);
        }

        try {
            $userInfoCong = MiniProgramService::getUserInfo($code);
            $session_key = $userInfoCong['session_key'];
        } catch (\Exception $e) {
            throw new OAuthException($e->getMessage());
        }

        if (!isset($userInfoCong['openid'])) {
            throw new OAuthException(410075);
        }

        //是否静默授权
        if (isset($options['silence']) && $options['silence'] === true) {
            return $userInfoCong;
        }

        if (empty($options['iv']) || empty($options['encryptedData'])) {
            throw new OAuthException(100100);
        }

        try {
            //解密获取用户信息
            $userInfo = MiniProgramService::encryptor($session_key, $options['iv'], $options['encryptedData']);
        } catch (\Exception $e) {
            if ($e->getCode() == '-41003') {
                throw new OAuthException(410077);
            }
        }

        return [$userInfoCong, $userInfo];
    }
}
