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
use app\services\system\lang\LangCountryServices;
use think\facade\App;

class LangCountry extends AuthController
{
    /**
     * @param App $app
     * @param LangCountryServices $services
     */
    public function __construct(App $app, LangCountryServices $services)
    {
        parent::__construct($app);
        $this->services = $services;
    }

    /**
     * 国家语言列表
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function langCountryList()
    {
        $where = $this->request->getMore([
            ['keyword', ''],
        ]);
        return app('json')->success($this->services->langCountryList($where));
    }

    /**
     * 设置国家语言类型表单
     * @param $id
     * @return mixed
     * @throws \FormBuilder\Exception\FormBuilderException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function langCountryForm($id)
    {
        return app('json')->success($this->services->langCountryForm($id));
    }

    /**
     * 地区语言修改
     * @param $id
     * @return mixed
     */
    public function langCountrySave($id)
    {
        $data = $this->request->postMore([
            ['name', ''],
            ['code', ''],
            ['lang_type_id', 0],
        ]);
        $this->services->langCountrySave($id, $data);
        return app('json')->success(100000);
    }

    public function langCountryDel($id)
    {
        $this->services->langCountryDel($id);
        return app('json')->success(100002);
    }
}