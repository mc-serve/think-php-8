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
use app\services\system\lang\LangCodeServices;
use think\facade\App;

class LangCode extends AuthController
{
    /**
     * @param App $app
     * @param LangCodeServices $services
     */
    public function __construct(App $app, LangCodeServices $services)
    {
        parent::__construct($app);
        $this->services = $services;
    }

    /**
     * 获取语言列表
     * @return mixed
     * @throws \ReflectionException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function langCodeList()
    {
        $where = $this->request->getMore([
            ['is_admin', 0],
            ['lang_type_id', 0],
            ['code', ''],
            ['remarks', '']
        ]);
        return app('json')->success($this->services->langCodeList($where));
    }

    /**
     * 获取语言详情
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function langCodeInfo()
    {
        [$code] = $this->request->getMore([
            ['code', ''],
        ], true);
        return app('json')->success($this->services->langCodeInfo($code));
    }

    /**
     * 新增编辑语言
     * @return mixed
     * @throws \Exception
     */
    public function langCodeSave()
    {
        $data = $this->request->postMore([
            ['is_admin', 0],
            ['code', ''],
            ['remarks', ''],
            ['edit', 0],
            ['list', []]
        ]);
        $this->services->langCodeSave($data);
        return app('json')->success(100000);
    }

    /**
     * 删除语言
     * @param $id
     * @return mixed
     */
    public function langCodeDel($id)
    {
        $this->services->langCodeDel($id);
        return app('json')->success(100002);
    }

    /**
     * 机器翻译
     * @return mixed
     * @throws \Throwable
     */
    public function langCodeTranslate()
    {
        [$text] = $this->request->postMore([
            ['text', '']
        ], true);
        if ($text == '') return app('json')->fail(100100);
        return app('json')->success($this->services->langCodeTranslate($text));
    }
}