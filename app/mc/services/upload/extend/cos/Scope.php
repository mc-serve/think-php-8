<?php
/**
 *  +----------------------------------------------------------------------
 *  | MC [ MC多应用系统，全产业链赋能 ]
 *  +----------------------------------------------------------------------
 *  | Copyright (c) 2022~2025 https://www.mc-serve.com All rights reserved.
 *  +----------------------------------------------------------------------
 *  | Licensed MC并不是自由软件，未经许可不能去掉MC相关版权
 *  +----------------------------------------------------------------------
 *  | Author: MC Team <cs@mc-serve.com>
 *  +----------------------------------------------------------------------
 */

namespace app\mc\services\upload\extend\cos;


class Scope
{
    protected $action;
    protected $bucket;
    protected $region;
    protected $resourcePrefix;
    protected $effect = 'allow';

    public function __construct($action, $bucket, $region, $resourcePrefix)
    {
        $this->action = $action;
        $this->bucket = $bucket;
        $this->region = $region;
        $this->resourcePrefix = $resourcePrefix;
    }

    public function set_effect($isAllow)
    {
        if ($isAllow) {
            $this->effect = 'allow';
        } else {
            $this->effect = 'deny';
        }
    }

    public function get_action()
    {
        if ($this->action == null) {
            throw new \Exception("action == null");
        }
        return $this->action;
    }

    public function get_resource()
    {
        if ($this->bucket == null) {
            throw new \Exception("bucket == null");
        }
        if ($this->region == null) {
            throw new \Exception("region == null");
        }
        if ($this->resourcePrefix == null) {
            throw new \Exception("resourcePrefix == null");
        }
        $index = strripos($this->bucket, '-');
        if ($index < 0) {
            throw new Exception("bucket is invalid: " . $this->bucket);
        }
        $appid = substr($this->bucket, $index + 1);
        if (!(strpos($this->resourcePrefix, '/') === 0)) {
            $this->resourcePrefix = '/' . $this->resourcePrefix;
        }
        return 'qcs::cos:' . $this->region . ':user_id/' . $appid . ':' . $this->bucket . $this->resourcePrefix;
    }

    public function get_effect()
    {
        return $this->effect;
    }

}
