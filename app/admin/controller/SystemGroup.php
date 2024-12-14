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

use app\services\system\config\SystemGroupDataServices;
use think\facade\App;
use app\admin\controller\AuthController;
use app\services\system\config\SystemGroupServices;

/**
 * 组合数据
 * Class SystemGroup
 * @package app\admin\controller
 */
class SystemGroup extends AuthController
{
    /**
     * 构造方法
     * SystemGroup constructor.
     * @param App $app
     * @param SystemGroupServices $services
     */
    public function __construct(App $app, SystemGroupServices $services)
    {
        parent::__construct($app);
        $this->services = $services;
    }

    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $where = $this->request->getMore([
            ['title', '']
        ]);
        return app('json')->success($this->services->getGroupList($where));
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        //
    }

    /**
     * 保存新建的资源
     *
     * @return \think\Response
     */
    public function save()
    {
        $params = $this->request->postMore([
            ['name', ''],
            ['config_name', ''],
            [['store_category_id', 'd'], 0],
            ['info', ''],
            ['typelist', []],
        ]);

        //数据组名称判断
        if (!$params['name']) {
            return app('json')->fail(400187);
        }
        if (!$params['config_name']) {
            return app('json')->fail(400274);
        }
        $data["name"] = $params['name'];
        $data["config_name"] = $params['config_name'];
        $data["info"] = $params['info'];
        $data["store_category_id"] = $params['store_category_id'];
        //字段信息判断
        if (!count($params['typelist']))
            return app('json')->fail(400294);
        else {
            $validate = ["name", "type", "title", "description"];
            foreach ($params["typelist"] as $key => $value) {
                foreach ($value as $name => $field) {
                    if (empty($field["value"]) && in_array($name, $validate))
                        return app('json')->fail("字段" . ($key + 1) . "：" . $field["placeholder"] . "不能为空！");
                    else
                        $data["fields"][$key][$name] = $field["value"];
                }
            }
        }
        $data["fields"] = json_encode($data["fields"]);
        $this->services->save($data);
        \app\mc\services\CacheService::clear();
        return app('json')->success(400295);
    }

    /**
     * 显示指定的资源
     *
     * @param int $id
     * @return \think\Response
     */
    public function read($id)
    {
        $info = $this->services->get($id);
        $fields = json_decode($info['fields'], true);
        $type_list = [];
        foreach ($fields as $key => $v) {
            $type_list[$key]['name']['value'] = $v['name'];
            $type_list[$key]['title']['value'] = $v['title'];
            $type_list[$key]['type']['value'] = $v['type'];
            $type_list[$key]['param']['value'] = $v['param'];
        }
        $info['typelist'] = $type_list;
        unset($info['fields']);
        return app('json')->success(compact('info'));
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param int $id
     * @return \think\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * 保存更新的资源
     *
     * @param int $id
     * @return \think\Response
     */
    public function update($id)
    {
        $params = $this->request->postMore([
            ['name', ''],
            ['config_name', ''],
            [['store_category_id', 'd'], 0],
            ['info', ''],
            ['typelist', []],
        ]);

        //数据组名称判断
        if (!$params['name']) return app('json')->fail(400187);
        if (!$params['config_name']) return app('json')->fail(400274);
        //判断ID是否存在，存在就是编辑，不存在就是添加
        if (!$id) {
            if ($this->services->count(['config_name' => $params['config_name']])) {
                return app('json')->fail(400296);
            }
        }
        $data["name"] = $params['name'];
        $data["config_name"] = $params['config_name'];
        $data["info"] = $params['info'];
        $data["store_category_id"] = $params['store_category_id'];
        //字段信息判断
        if (!count($params['typelist']))
            return app('json')->fail(400294);
        else {
            $validate = ["name", "type", "title", "description"];
            foreach ($params["typelist"] as $key => $value) {
                foreach ($value as $name => $field) {
                    if (empty($field["value"]) && in_array($name, $validate))
                        return app('json')->fail(400297);
                    else
                        $data["fields"][$key][$name] = $field["value"];
                }
            }
        }
        $data["fields"] = json_encode($data["fields"]);
        $this->services->update($id, $data);
        \app\mc\services\CacheService::clear();
        return app('json')->success(400295);
    }

    /**
     * 删除指定资源
     *
     * @param int $id
     * @return \think\Response
     */
    public function delete($id, SystemGroupDataServices $services)
    {
        if (!$this->services->delete($id))
            return app('json')->fail(100008);
        else {
            $services->delete($id, 'gid');
            return app('json')->success(100002);
        }
    }

    /**
     * 获取组合数据
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getGroup()
    {
        return app('json')->success($this->services->getGroupList(['store_category_id' => 1], ['id', 'name', 'config_name'])['list']);
    }
}
