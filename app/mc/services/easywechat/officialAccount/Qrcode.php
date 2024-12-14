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

class Qrcode
{
    public $data = [];
    public $app = null;

    public function __construct($app)
    {
        $this->app = $app;
    }

    public function temporary($scene_id, $expire_seconds) {
        $this->data = [
            "expire_seconds" => $expire_seconds,
            "action_name" => "QR_SCENE",
            "action_info" => [
                "scene" => [
                    "scene_id" => $scene_id
                ]
                ],
                "url" => "",
        ];
        $api = $this->app->getClient();
        $response = $api->postJson('/cgi-bin/qrcode/create?access_token=' . $this->app->getMyToken(), $this->data);
        if ($response->isFailed()) {
            throw new AdminException(4005521, $response->getContent());
        }
        $this->data['ticket'] = $response['ticket'];
        $this->data['expire_seconds'] = $response['expire_seconds'];
        $this->data['url'] = $response['url'];
        return $this;
    }

    public function url() {
        return $this->data['url'];
    }

    public function toArray() {
        return $this->data;
    }
}