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

namespace app\system\services;


use app\mc\basic\BaseServices;
use app\mc\exceptions\AdminException;
use app\mc\services\HttpService;

/**
 * 商业授权
 * Class SystemAuthServices
 * @package app\system\services
 */
class SystemAuthServices extends BaseServices
{

    /**
     * 申请授权
     * @param array $data
     * @return bool
     */
    public function authApply(array $data)
    {
        $res = HttpService::postRequest('http://authorize.mc-serve.com/api/auth_apply', $data);
        if ($res === false) {
            throw new AdminException(100028);
        }
        $res = json_decode($res, true);
        if (isset($res['status'])) {
            if ($res['status'] == 400) {
                throw new AdminException(100028);
            } else {
                return true;
            }
        }
    }
}
