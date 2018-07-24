<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/





Route::get('/', function () {
    return view('welcome');
});

Route::get('/test', function () {
    \Illuminate\Support\Facades\Auth::guard()->loginUsingId(1);
});

Route::get('/test1', function (\Illuminate\Http\Request $request) {
    var_dump($request->user());
    die;
});

Route::get('login',function (){
    return redirect('/admin');
});

Route::group(['namespace' => 'Admin'], function () {

    Route::get('/admin', 'AmsController@index');
    Route::get('/admin/login', 'AmsController@login');
    Route::get('/admin/callback', 'AmsController@callback');
    Route::get('/admin/logout', 'AmsController@logout');


    Route::get('index', ['as' => 'admin.index', 'uses' => function () {
        return redirect('/admin/log-viewer');
    }]);

    Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'menu']], function () {

        //权限管理路由
        Route::get('permission/{cid}/create', ['as' => 'admin.permission.create', 'uses' => 'PermissionController@create']);
        Route::get('permission/manage', ['as' => 'admin.permission.manage', 'uses' => 'PermissionController@index']);
        Route::get('permission/{cid?}', ['as' => 'admin.permission.index', 'uses' => 'PermissionController@index']);
        Route::post('permission/index', ['as' => 'admin.permission.index', 'uses' => 'PermissionController@index']); //查询
        Route::resource('permission', 'PermissionController', ['names' => ['update' => 'admin.permission.edit', 'store' => 'admin.permission.create']]);


        //角色管理路由
        Route::get('role/index', ['as' => 'admin.role.index', 'uses' => 'RoleController@index']);
        Route::post('role/index', ['as' => 'admin.role.index', 'uses' => 'RoleController@index']);
        Route::resource('role', 'RoleController', ['names' => ['update' => 'admin.role.edit', 'store' => 'admin.role.create']]);


        //用户管理路由
        Route::get('user/index', ['as' => 'admin.user.index', 'uses' => 'UserController@index']);  //用户管理
        Route::post('user/index', ['as' => 'admin.user.index', 'uses' => 'UserController@index']);
        Route::resource('user', 'UserController', ['names' => ['update' => 'admin.role.edit', 'store' => 'admin.role.create']]);

        //视频管理路由
        Route::get('video/index', ['as' => 'admin.video.index', 'uses' => 'VideoController@index']);
        Route::post('video/index', ['as' => 'admin.video.index', 'uses' => 'VideoController@index']);
        Route::resource('video', 'VideoController', ['names' => ['update' => 'admin.video.edit', 'store' => 'admin.video.create']]);

        //阿里云视频缓存
        Route::get('/push', ['as' => 'admin.video.push', 'uses' => 'VideoController@push'])->middleware('cors');
        Route::get('/refresh', ['as' => 'admin.video.refresh', 'uses' => 'VideoController@refresh'])->middleware('cors');

        //上传视频管理路由
        Route::get('upload/index', ['as' => 'admin.upload.index', 'uses' => 'UploadController@index']);
        Route::post('upload/index', ['as' => 'admin.upload.index', 'uses' => 'UploadController@index']);
        Route::resource('upload', 'UploadController', ['names' => ['update' => 'admin.upload.edit', 'store' => 'admin.upload.create']]);
        //视频数据统计
        Route::get('top/index', ['as' => 'admin.top.index', 'uses' => 'TopController@index']);
        Route::get('total/index', ['as' => 'admin.total.index', 'uses' => 'TotalController@index']);
        Route::get('avg/index', ['as' => 'admin.avg.index', 'uses' => 'AvgController@index']);
        Route::get('bps/index', ['as' => 'admin.bps.index', 'uses' => 'BpsController@index']);//带宽数据
        Route::get('flow/index', ['as' => 'admin.flow.index', 'uses' => 'FlowController@index']);//流量数据

    });
});


