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

namespace app\mc\services\easywechat\officialAccount;

use app\mc\exceptions\AdminException;

class AccessToken implements \EasyWeChat\Kernel\Contracts\AccessToken
{
    public function getToken(): string
    {
        dump(parent::getAccessToken());exit();
        // 你的逻辑
        return 'your token';
    }

    public function toQuery(): array
    {
        return ['access_token' => $this->getToken()];
    }
}