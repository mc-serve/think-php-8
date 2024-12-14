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
use app\system\services\admin\SystemRoleServices;
use app\system\services\SystemMenusServices;
use app\mc\services\CacheService;
use think\facade\App;

/**
 * Class SystemRole
 * @package app\admin\controller
 */
class SystemRole extends AuthController
{
    /**
     * SystemRole constructor.
     * @param App $app
     * @param SystemRoleServices $services
     */
    public function __construct(App $app, SystemRoleServices $services)
    {
        parent::__construct($app);
        $this->services = $services;
    }

    /**
     * 显示资源列表
     * @return mixed
     */
    public function index()
    {
        $where = $this->request->getMore([
            ['status', ''],
            ['role_name', ''],
        ]);
        $where['level'] = $this->adminInfo['level'] + 1;
        return app('json')->success($this->services->getRoleList($where));
    }

    /**
     * 显示创建资源表单页
     * @param SystemMenusServices $services
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function create(SystemMenusServices $services)
    {
        $menus = $services->getmenus($this->adminInfo['level'] == 0 ? [] : $this->adminInfo['roles']);
        return app('json')->success(compact('menus'));
    }

    /**
     * 保存新建的资源
     *
     * @return \think\Response
     */
    public function save($id)
    {
        $data = $this->request->postMore([
            'role_name',
            ['status', 0],
            ['checked_menus', [], '', 'rules']
        ]);
        if (!$data['role_name']) return app('json')->fail(400220);
        if (!is_array($data['rules']) || !count($data['rules']))
            return app('json')->fail(400221);

        $data['rules'] = implode(',', $data['rules']);
        if ($id) {
            if (!$this->services->update($id, $data)) return app('json')->fail(100007);
            CacheService::clear();
            return app('json')->success(100001);
        } else {
            $data['level'] = $this->adminInfo['level'] + 1;
            if (!$this->services->save($data)) return app('json')->fail(400223);
            CacheService::clear();
            return app('json')->success(400222);
        }
    }

    /**
     * 显示编辑资源表单页
     * @param SystemMenusServices $services
     * @param $id
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function edit(SystemMenusServices $services, $id)
    {
        $role = $this->services->get($id);
        if (!$role) {
            return app('json')->fail(100100);
        }
        $menus = $services->getMenus($this->adminInfo['level'] == 0 ? [] : $this->adminInfo['roles'], explode(',', $role['rules']));
        return app('json')->success(['role' => $role->toArray(), 'menus' => $menus]);
    }

    /**
     * 删除指定资源
     * @param SystemAdminServices $adminServices
     * @param $id
     * @return mixed
     */
    public function delete(SystemAdminServices $adminServices, $id)
    {
        if ($adminServices->checkRoleUse($id)) {
            return app('json')->fail(400754);
        }
        if (!$this->services->delete($id))
            return app('json')->fail(100008);
        else {
            CacheService::clear();
            return app('json')->success(100002);
        }
    }

    /**
     * 修改状态
     * @param $id
     * @param $status
     * @return mixed
     */
    public function set_status($id, $status)
    {
        if (!$id) {
            return app('json')->fail(100100);
        }
        $role = $this->services->get($id);
        if (!$role) {
            return app('json')->fail(400199);
        }
        $role->status = $status;
        if ($role->save()) {
            CacheService::clear();
            return app('json')->success(100001);
        } else {
            return app('json')->fail(100007);
        }
    }
}
