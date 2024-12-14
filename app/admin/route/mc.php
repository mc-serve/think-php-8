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

use think\facade\Route;
use think\facade\Config;
use think\Response;
use app\http\middleware\AllowOriginMiddleware;

/**
 * 无需授权的接口
 */
Route::group('mc', function () {
    //升级程序
    Route::get('upgrade', 'UpgradeController/index');
    Route::get('upgrade/run', 'UpgradeController/upgrade');
    //用户名密码登录
    Route::post('login', 'Login/login')->name('AdminLogin')->option(['real_name' => '下载表备份记录']);
    //后台登录页面数据
    Route::get('login/info', 'Login/info')->option(['real_name' => '登录信息']);
    //下载文件
    Route::get('download/:key', 'Mc/download')->option(['real_name' => '下载文件']);
    //验证码
    Route::get('captcha_pro', 'Login/captcha')->name('')->option(['real_name' => '获取验证码']);
    //获取验证码
    Route::get('ajcaptcha', 'Login/ajcaptcha')->name('ajcaptcha')->option(['real_name' => '获取验证码']);
    //一次验证
    Route::post('ajcheck', 'Login/ajcheck')->name('ajcheck')->option(['real_name' => '一次验证']);
    //获取客服数据
    Route::get('get_workerman_url', 'Mc/getWorkerManUrl')->option(['real_name' => '获取客服数据']);
    //测试
    Route::get('index', 'Test/index')->option(['real_name' => '测试地址']);

    //扫码上传图片
    Route::post('image/scan_upload', 'Mc/scanUpload')->option(['real_name' => '扫码上传图片']);
    //路由导入
    Route::get('route/import_api', 'Mc/import')->option(['real_name' => '路由导入']);
    //消息提醒
    Route::get('jnotice', 'Mc/jnotice')->option(['real_name' => '消息提醒']);
    //验证授权
    Route::get('check_auth', 'Mc/auth')->option(['real_name' => '验证授权']);
    //申请授权
    Route::post('auth_apply', 'Mc/auth_apply')->option(['real_name' => '申请授权']);
    //授权
    Route::get('auth', 'Mc/auth')->option(['real_name' => '授权信息']);
    //获取左侧菜单
    Route::get('menus', 'v1.setting.SystemMenus/menus')->option(['real_name' => '左侧菜单']);
    //获取搜索菜单列表
    Route::get('menusList', 'Mc/menusList')->option(['real_name' => '搜索菜单列表']);
    //获取logo
    Route::get('logo', 'Mc/getLogo')->option(['real_name' => '获取logo']);
    //查询版权
    Route::get('copyright', 'Mc/copyright')->option(['real_name' => '申请版权']);

})->middleware(AllowOriginMiddleware::class)->option(['mark' => 'login', 'mark_name' => '登录相关']);

/**
 * miss 路由
 */
Route::miss(function () {
    if (app()->request->isOptions()) {
        $header = Config::get('cookie.header');
        $header['Access-Control-Allow-Origin'] = app()->request->header('origin');
        return Response::create('ok')->code(200)->header($header);
    } else
        return Response::create()->code(404);
});
