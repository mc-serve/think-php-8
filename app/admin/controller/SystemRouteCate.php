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

namespace app\admin\controller;


use app\admin\controller\AuthController;
use app\services\system\SystemRouteCateServices;
use app\services\system\SystemRouteServices;
use think\facade\App;
use think\Request;

/**
 * Class SystemRouteCate
 * @author 等风来
 * @email 136327134@qq.com
 * @date 2023/4/6
 * @package app\admin\controller
 */
class SystemRouteCate extends AuthController
{

    /**
     * SystemRouteCate constructor.
     * @param App $app
     * @param SystemRouteCateServices $services
     */
    public function __construct(App $app, SystemRouteCateServices $services)
    {
        parent::__construct($app);
        $this->services = $services;
    }

    /**
     * @return \think\Response
     * @author 等风来
     * @email 136327134@qq.com
     * @date 2023/4/6
     */
    public function index()
    {
        return app('json')->success($this->services->getAllList());
    }

    /**
     * @return \think\Response
     * @author 等风来
     * @email 136327134@qq.com
     * @date 2023/4/6
     */
    public function create()
    {
        return app('json')->success($this->services->getFrom(0, $this->request->get('app_name', 'admin')));
    }

    /**
     * @param Request $request
     * @return \think\Response
     * @author 等风来
     * @email 136327134@qq.com
     * @date 2023/4/6
     */
    public function save(Request $request)
    {
        $data = $request->postMore([
            ['path', []],
            ['name', ''],
            ['sort', 0],
            ['app_name', ''],
        ]);

        if (!$data['name']) {
            return app('json')->fail(500037);
        }

        $data['add_time'] =date("Y-m-d H:i:s");
        $data['pid'] = $data['path'][count($data['path']) - 1] ?? 0;
        $this->services->save($data);


        return app('json')->success(100000);

    }

    /**
     * @param $id
     * @return \think\Response
     * @author 等风来
     * @email 136327134@qq.com
     * @date 2023/4/6
     */
    public function edit($id)
    {
        return app('json')->success($this->services->getFrom($id, $this->request->get('app_name', 'admin')));
    }

    /**
     * @param Request $request
     * @param $id
     * @return \think\Response
     * @author 等风来
     * @email 136327134@qq.com
     * @date 2023/4/6
     */
    public function update(Request $request, $id)
    {
        $data = $request->postMore([
            ['path', []],
            ['name', ''],
            ['sort', 0],
            ['app_name', ''],
        ]);

        if (!$data['name']) {
            return app('json')->fail(500037);
        }

        $data['pid'] = $data['path'][count($data['path']) - 1] ?? 0;
        $this->services->update($id, $data);

        return app('json')->success(100001);
    }

    /**
     * @param SystemRouteServices $service
     * @param $id
     * @return \think\Response
     * @author 等风来
     * @email 136327134@qq.com
     * @date 2023/4/6
     */
    public function delete(SystemRouteServices $service, $id)
    {
        if (!$id) {
            return app('json')->fail(500035);
        }

        if ($service->count(['store_category_id' => $id])) {
            return app('json')->fail(500038);
        }

        $this->services->delete($id);

        return app('json')->success(100002);
    }
}
