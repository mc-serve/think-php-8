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


use app\mc\exceptions\ApiException;

/**
 * Class AccessTokenServeService
 * @package app\mc\services
 */
class AccessTokenServeService extends HttpService
{
    /**
     * 配置
     * @var string
     */
    protected $account;

    /**
     * @var string
     */
    protected $secret;

    /**
     * @var string
     */
    protected $accessToken;

    /**
     * @var string
     */
    protected $cacheTokenPrefix = "_mc_plat";

    /**
     * @var string
     */
    protected $apiHost = 'http://sms.mc-serve.com/api/';

    /**
     * 沙盒地址
     * @var string
     */
    protected $sandBoxApi = 'https://api_v2.mc-serve.com/api/';

    /**
     * 沙盒模式
     * @var bool
     */
    protected $sandBox = false;

    /**
     * 登录接口
     */
    const USER_LOGIN = "v2/user/login";


    /**
     * AccessTokenServeService constructor.
     * @param string $account
     * @param string $secret
     */
    public function __construct(string $account, string $secret)
    {
        $this->account = $account;
        $this->secret = $secret;
    }

    /**
     * 获取配置
     * @return array
     */
    public function getConfig()
    {
        return [
            'access_key' => $this->account,
            'secret_key' => $this->secret
        ];
    }

    /**
     * 获取缓存token
     * @return mixed
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function getToken()
    {
        $accessTokenKey = md5($this->account . '_v2_' . $this->secret . $this->cacheTokenPrefix);
        $cacheToken = CacheService::get($accessTokenKey);
        if (!$cacheToken) {
            $getToken = $this->getTokenFromServer();
            CacheService::set($accessTokenKey, $getToken['access_token'], $getToken['expires_in'] - 60);
            $cacheToken = $getToken['access_token'];
        }
        $this->accessToken = $cacheToken;

        return $cacheToken;

    }

    /**
     * 从服务器获取token
     * @return mixed
     */
    public function getTokenFromServer()
    {
        $params = [
            'access_key' => $this->account,
            'secret_key' => $this->secret,
        ];
        $response = $this->postRequest($this->get(self::USER_LOGIN), $params);
        $response = json_decode($response, true);
        if (!$response) {
            throw new ApiException(410085, ['msg' => '']);
        }
        if ($response['status'] === 200) {
            return $response['data'];
        } else {
            throw new ApiException(410085, ['msg' => ':' . $response['msg']]);
        }
    }

    /**
     * 请求
     * @param string $url
     * @param array $data
     * @param string $method
     * @param bool $isHeader
     * @return array|mixed
     */
    public function httpRequest(string $url, array $data = [], string $method = 'POST', bool $isHeader = true, array $header = [])
    {
        if ($isHeader) {
            $this->getToken();
            if (!$this->accessToken) {
                throw new ApiException(410086);
            }
            $header = array_merge($header, ['Authorization:Bearer-' . $this->accessToken]);
        }

        $res = $this->request($this->get($url), $method, $data, $header);
        if (!$res) {
            throw new ApiException(410087);

        }
        $result = json_decode($res, true) ?: false;
        if (!isset($result['status']) || $result['status'] != 200) {
            throw new ApiException($result['msg']);
        }
        return $result['data'] ?? [];

    }

    /**
     * @param string $apiUrl
     * @return string
     */
    public function get(string $apiUrl = '')
    {
        if ($this->sandBox) {
            return $this->sandBoxApi . $apiUrl;
        }
        return $this->apiHost . $apiUrl;
    }
}
