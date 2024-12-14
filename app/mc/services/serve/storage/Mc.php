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

namespace app\mc\services\serve\storage;


use app\mc\basic\BaseStorage;
use app\mc\exceptions\AdminException;
use app\mc\services\AccessTokenServeService;

/**
 * Class Mc
 * @package app\mc\services\serve\storage
 */
class Mc extends BaseStorage
{

    protected $accessToken;

    /**
     * Mc constructor.
     * @param string $name
     * @param AccessTokenServeService $service
     * @param string|null $configFile
     */
    public function __construct(string $name, AccessTokenServeService $service, string $configFile = null)
    {
        $this->accessToken = $service;
    }

    protected function initialize(array $config)
    {
        // TODO: Implement initialize() method.
    }

    /**
     * 获取用户信息
     * @param string $account
     * @param string $secret
     * @return array|mixed
     */
    public function getUser()
    {
        return $this->accessToken->httpRequest('v2/user/info');
    }

    /**
     * 用量记录
     * @param int $page
     * @param int $limit
     * @param int $type
     * @return array|mixed
     */
    public function record(int $page, int $limit, int $type, $status = '')
    {
        $typeContent = [1 => 'sms', 2 => 'expr_dump', 3 => 'expr_query', 4 => 'copy'];
        if (!isset($typeContent[$type])) {
            throw new AdminException(100100);
        }
        $data = ['page' => $page, 'limit' => $limit, 'type' => $typeContent[$type]];
        if ($type == 1 && $status != '') {
            $data['status'] = $status;
        }
        return $this->accessToken->httpRequest('user/record', $data);
    }
}
