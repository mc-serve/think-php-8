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
namespace app\admin\controller;

use app\admin\controller\AuthController;
use app\system\services\admin\SystemAdminServices;
use app\mc\services\CacheService;
use think\facade\{App, Config};

/**
 * Class SystemAdmin
 * @package app\admin\controller
 */
class SystemAdmin extends AuthController
{
    /**
     * SystemAdmin constructor.
     * @param App $app
     * @param SystemAdminServices $services
     */
    public function __construct(App $app, SystemAdminServices $services)
    {
        parent::__construct($app);
        $this->services = $services;
    }

    /**
     * 显示管理员资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $where = $this->request->getMore([
            ['name', '', '', 'account_like'],
            ['roles', ''],
            ['is_del', 1],
            ['status', '']
        ]);
        $where['level'] = $this->adminInfo['level'] + 1;
        return app('json')->success($this->services->getAdminList($where));
    }

    /**
     * 创建表单
     * @return mixed
     * @throws \FormBuilder\Exception\FormBuilderException
     */
    public function create()
    {
        return app('json')->success($this->services->createForm($this->adminInfo['level'] + 1));
    }

    /**
     * 保存管理员
     * @return mixed
     */
    public function save()
    {
        $data = $this->request->postMore([
            ['account', ''],
            ['conf_pwd', ''],
            ['pwd', ''],
            ['real_name', ''],
            ['roles', []],
            ['status', 0],
        ]);

        $this->validate($data, \app\admin\validate\setting\SystemAdminValidata::class);

        $data['level'] = $this->adminInfo['level'] + 1;
        $this->services->create($data);
        return app('json')->success(100000);
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param int $id
     * @return \think\Response
     */
    public function edit($id)
    {
        if (!$id) {
            return app('json')->fail(400182);
        }

        return app('json')->success($this->services->updateForm($this->adminInfo['level'] + 1, (int)$id));
    }

    /**
     * 修改管理员信息
     * @param $id
     * @return mixed
     */
    public function update($id)
    {
        $data = $this->request->postMore([
            ['account', ''],
            ['conf_pwd', ''],
            ['pwd', ''],
            ['real_name', ''],
            ['roles', []],
            ['status', 0],
        ]);

        $this->validate($data, \app\admin\validate\setting\SystemAdminValidata::class, 'update');

        if ($this->services->save((int)$id, $data)) {
            return app('json')->success(100001);
        } else {
            return app('json')->fail(100007);
        }
    }

    /**
     * 删除管理员
     * @param $id
     * @return mixed
     */
    public function delete($id)
    {
        if (!$id) return app('json')->fail(100100);
        if ($this->services->update((int)$id, ['is_del' => 1, 'status' => 0]))
            return app('json')->success(100002);
        else
            return app('json')->fail(100008);
    }

    /**
     * 修改状态
     * @param $id
     * @param $status
     * @return mixed
     */
    public function set_status($id, $status)
    {
        $this->services->update((int)$id, ['status' => $status]);
        return app('json')->success(100014);
    }

    /**
     * 获取当前登陆管理员的信息
     * @return mixed
     */
    public function info()
    {
        return app('json')->success($this->adminInfo);
    }

    /**
     * 修改当前登陆admin信息
     * @return mixed
     */
    public function update_admin()
    {
        $data = $this->request->postMore([
            ['real_name', ''],
            ['head_pic', ''],
            ['pwd', ''],
            ['new_pwd', ''],
            ['conf_pwd', ''],
        ]);

        if ($data['pwd']) {
            if (!preg_match('/^(?![^a-zA-Z]+$)(?!\D+$).{6,}$/', $data['new_pwd'])) {
                return app('json')->fail(400183);
            }
        }

        if ($this->services->updateAdmin($this->adminId, $data))
            return app('json')->success(100001);
        else
            return app('json')->fail(100007);
    }

    /**
     * 修改当前登陆admin的文件管理密码
     * @return mixed
     */
    public function set_file_password()
    {
        $data = $this->request->postMore([
            ['file_pwd', ''],
            ['conf_file_pwd', ''],
        ]);
        if (!preg_match('/^(?![^a-zA-Z]+$)(?!\D+$).{6,}$/', $data['file_pwd'])) {
            return app('json')->fail(400183);
        }
        if ($this->services->setFilePassword($this->adminId, $data))
            return app('json')->success(100001);
        else
            return app('json')->fail(100007);
    }

    /**
     * 退出登陆
     * @return mixed
     */
    public function logout()
    {
        $key = trim(ltrim($this->request->header(Config::get('cookie.token_name')), 'Bearer'));
        CacheService::delete(md5($key));
        return app('json')->success();
    }
}
