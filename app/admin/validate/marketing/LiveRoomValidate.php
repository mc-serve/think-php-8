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
namespace app\admin\validate\marketing;

use think\Validate;

class LiveRoomValidate extends Validate
{

    /**
     * 定义验证规则
     * 格式：'字段名'    =>    ['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'name' => 'require',
        'cover_img' => 'require',
        'share_img' => 'require',
        'anchor_wechat' => 'require',
        'start_time' => 'require|checkStartTime',
        'phone' => 'require|checkPhone',
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名'    =>    '错误信息'
     *
     * @var array
     */
    protected $message = [
        'name.require' => '400342',
        'cover_img.require' => '400343',
        'share_img.require' => '400344',
        'anchor_wechat.require' => '400345',
        'start_time.require' => '400346',
        'start_time.checkStartTime' => '400346',
        'phone.require' => '400333',
        'phone.checkPhone' => '400252',
    ];

    protected function checkPhone($value): bool
    {
        return check_phone($value) == true;
    }

    protected function checkStartTime($value): bool
    {
        return count($value) == 2;
    }

    protected $scene = [
        'save' => ['name', 'cover_img', 'share_img', 'anchor_wechat', 'start_time', 'phone'],
    ];
}
