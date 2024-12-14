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


use app\mc\exceptions\AdminException;
use app\mc\services\CacheService;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use think\facade\Env;

/**
 * Jwt
 * Class JwtAuth
 * @package app\mc\utils
 */
class JwtAuth
{

    /**
     * token
     * @var string
     */
    protected $token;

    /**
     * 获取token
     * @param int|string $id
     * @param string $type
     * @param array $params
     * @return array
     */
    public function getToken($id, string $type, array $params = []): array
    {
        $host = app()->request->host();
        $time = time();
        $exp_time = strtotime('+ 30day');
        $params += [
            'iss' => $host,
            'aud' => $host,
            'iat' => $time,
            'nbf' => $time,
            'exp' => $exp_time,
        ];
        $params['jti'] = compact('id', 'type');
        $token = JWT::encode($params, Env::get('app.app_key', 'default'), 'HS256');

        return compact('token', 'params');
    }

    /**
     * 解析token
     * @param string $jwt
     * @return array
     */
    public function parseToken(string $jwt): array
    {
        $this->token = $jwt;
        list($headb64, $bodyb64, $cryptob64) = explode('.', $this->token);
        $payload = JWT::jsonDecode(JWT::urlsafeB64Decode($bodyb64));
        return [$payload->jti->id, $payload->jti->type, $payload->pwd ?? ''];
    }

    /**
     * 验证token
     */
    public function verifyToken()
    {
        JWT::$leeway = 60;

        JWT::decode($this->token, new Key(Env::get('app.app_key', 'default'), 'HS256'));

        $this->token = null;
    }

    /**
     * 获取token并放入令牌桶
     * @param $id
     * @param string $type
     * @param array $params
     * @return array
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function createToken($id, string $type, array $params = [])
    {
        $tokenInfo = $this->getToken($id, $type, $params);
        $exp = $tokenInfo['params']['exp'] - $tokenInfo['params']['iat'] + 60;
        $res = CacheService::set(md5($tokenInfo['token']), ['id' => $id, 'type' => $type, 'token' => $tokenInfo['token'], 'exp' => $exp], (int)$exp, $type);
        if (!$res) {
            throw new AdminException(100023);
        }
        return $tokenInfo;
    }
}
