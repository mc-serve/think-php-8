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

/**
 * 文件下载、导出相关路由
 */
Route::group(function () {
    //下载备份记录表
    Route::get('backup/download', 'v1.system.SystemDatabackup/downloadFile')->option(['real_name' => '下载表备份记录']);
    //首页统计数据
    Route::get('admin/header', 'Admin/homeStatics')->option(['real_name' => '首页统计数据']);
    //首页订单图表
    Route::get('admin/order', 'Admin/orderChart')->option(['real_name' => '首页订单图表']);
    //首页用户图表
    Route::get('admin/user', 'Admin/userChart')->option(['real_name' => '首页用户图表']);
    //首页交易额排行
    Route::get('admin/rank', 'Admin/purchaseRanking')->option(['real_name' => '首页交易额排行']);
    //消息提醒
    Route::get('jnotice', 'Admin/jnotice')->option(['real_name' => '消息提醒']);
    //验证授权
    Route::get('check_auth', 'Admin/auth')->option(['real_name' => '验证授权']);
    //申请授权
    Route::post('auth_apply', 'Admin/auth_apply')->option(['real_name' => '申请授权']);
    //授权
    Route::get('auth', 'Admin/auth')->option(['real_name' => '授权信息']);
    //获取左侧菜单
    Route::get('menus', 'v1.setting.SystemMenus/menus')->option(['real_name' => '左侧菜单']);
    //获取搜索菜单列表
    Route::get('menusList', 'Admin/menusList')->option(['real_name' => '搜索菜单列表']);
    //获取logo
    Route::get('logo', 'Admin/getLogo')->option(['real_name' => '获取logo']);
    //查询版权
    Route::get('copyright', 'Admin/copyright')->option(['real_name' => '申请版权']);
    //保存版权
    Route::post('copyright', 'Admin/saveCopyright')->option(['real_name' => '保存版权']);
})->middleware([
    \app\http\middleware\AllowOriginMiddleware::class,
    \app\admin\middleware\AdminAuthTokenMiddleware::class,
    \app\admin\middleware\AdminCheckRoleMiddleware::class,
    \app\admin\middleware\AdminLogMiddleware::class
])->option(['mark' => 'common', 'mark_name' => '系统数据']);

