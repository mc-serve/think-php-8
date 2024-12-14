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
use app\system\services\SystemRouteServices;
use app\mc\services\CacheService;
use think\facade\App;

/**
 * Class SystemRoute
 * @author 等风来
 * @email 136327134@qq.com
 * @date 2023/4/6
 * @package app\admin\controller
 */
class SystemRoute extends AuthController
{

    /**
     * SystemRoute constructor.
     * @param App $app
     * @param SystemRouteServices $services
     */
    public function __construct(App $app, SystemRouteServices $services)
    {
        parent::__construct($app);
        $this->services = $services;
    }

    /**
     * 同步路由权限
     * @param string $appName
     * @return \think\Response
     * @author 等风来
     * @email 136327134@qq.com
     * @date 2023/4/6
     */
    public function syncRoute(string $appName = 'admin')
    {
        $this->services->syncRoute($appName);

        return app('json')->success(100038);
    }

    /**
     * 列表数据
     * @return \think\Response
     * @author 等风来
     * @email 136327134@qq.com
     * @date 2023/4/7
     */
    public function index()
    {
        $where = $this->request->getMore([
            ['name_like', ''],
            ['app_name', 'admin']
        ]);

        return app('json')->success($this->services->getList($where));
    }

    /**
     * tree数据
     * @return \think\Response
     * @author 等风来
     * @email 136327134@qq.com
     * @date 2023/4/7
     */
    public function tree()
    {
        [$name, $appName] = $this->request->getMore([
            ['name_like', ''],
            ['app_name', 'admin']
        ], true);

        return app('json')->success($this->services->getTreeList($appName, $name));
    }


    /**
     * @return \think\Response
     * @author 等风来
     * @email 136327134@qq.com
     * @date 2023/4/7
     */
    public function save($id = 0)
    {
        $data = $this->request->postMore([
            ['store_category_id', 0],
            ['name', ''],
            ['path', ''],
            ['method', ''],
            ['type', 0],
            ['app_name', ''],
            ['query', []],
            ['header', []],
            ['request', []],
            ['response', []],
            ['request_example', []],
            ['response_example', []],
            ['describe', ''],
            ['error_code', []],
        ]);

//        if (!$data['name']) {
//            return app('json')->fail(500031);
//        }
//        if (!$data['path']) {
//            return app('json')->fail(500032);
//        }
//        if (!$data['method']) {
//            return app('json')->fail(500033);
//        }
//        if (!$data['app_name']) {
//            return app('json')->fail(500034);
//        }
        if ($id) {
            $this->services->update($id, $data);
        } else {
            $data['add_time'] = date('Y-m-d H:i:s');
            $this->services->save($data);
        }
        CacheService::clear();

        return app('json')->success($id ? 100001 : 100021);
    }

    /**
     * @param $id
     * @return \think\Response
     * @author 等风来
     * @email 136327134@qq.com
     * @date 2023/4/7
     */
    public function read($id)
    {
        return app('json')->success($this->services->getInfo((int)$id));
    }

    /**
     * @param $id
     * @return \think\Response
     * @author 等风来
     * @email 136327134@qq.com
     * @date 2023/4/7
     */
    public function delete($id)
    {
        if (!$id) {
            return app('json')->fail(500035);
        }

        $this->services->destroy($id);

        return app('json')->success(100002);
    }
}
