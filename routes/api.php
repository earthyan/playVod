<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::get('/', function (){
    echo  "hello world";
});

Route::group(['namespace' => 'Api'],function () {
    //自定义接口
    Route::get('/transmit/{key}','VideoController@transmit'); //视频请求转发
    Route::get('/transform','VideoController@transform'); //视频根据时间切换脚本
    Route::get('/storeVideo','VideoController@storeVideo');//存储视频

    Route::get('player',function (){
        return view('player');
    });
    Route::get('playerdemo',function (){
        return view('playerdemo');
    });
    Route::get('iframe',function (){
        return view('iframe');
    });
    //阿里云视频接口
    Route::get('/playAuth','VodController@getPlayAuth'); //获取阿里云视频凭证
    Route::get('/playInfo','VodController@getPlayInfo'); //获取阿里云视频信息
    Route::get('/PushObjectCache','VodController@PushObjectCache'); //阿里云视频预热缓存
    Route::get('/RefreshObjectCache','VodController@RefreshObjectCache'); //阿里云视频刷新缓存
    Route::post('/CreateUploadVideo','VodController@CreateUploadVideo'); //阿里云视频上传凭证
    Route::post('/RefreshUploadVideo','VodController@RefreshUploadVideo'); //阿里云视频上传凭证

    Route::get('/ApiUploadVideo','VodController@ApiUploadVideo'); //阿里云对外视频上传接口


    Route::get('/flow','DataController@flow'); //阿里云对外视频上传接口

    //upload
    Route::any('upload/auth', 'UploadController@auth');
    Route::any('upload/initMulti', 'UploadController@initMulti');
    Route::any('upload/part', 'UploadController@part');
    Route::any('upload/complete', 'UploadController@complete');

    //test
    Route::get('test/test1', 'TestController@test1');
    Route::get('test/test2', 'TestController@test2');
    Route::get('test/test3', 'TestController@test3');
    Route::get('test/test4', 'TestController@test4');


});