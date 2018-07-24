@extends('admin.layouts.base')

@section('title','控制面板')

@section('pageHeader','控制面板')

@section('pageDesc','DashBoard')

@section('content')
    <div class="main animsition">
        <div class="container-fluid">

            <div class="row">
                <div class="">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">上传视频</h3>
                        </div>
                        <div class="panel-body">
                            @include('admin.partials.errors')
                            @include('admin.partials.success')
                            @include('admin.upload._form')
                            <div class="form-horizontal">
                                <form action="" enctype="multipart/form-data">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="hidden" name="cove_image"/>
                                    <div class="form-group">
                                        <label for="tag" class="col-md-3 control-label">视频标题</label>
                                        <div class="col-md-5">
                                            <input type="text" class="form-control" name="title" id="title" value="{{ $Title }}" placeholder="不必填 默认视频文件名称" autofocus>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="tag" class="col-md-3 control-label">优酷ID</label>
                                        <div class="col-md-5">
                                            <input type="text" class="form-control" name="youku_id" id="youku_id"  placeholder="可选" autofocus>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="tag" class="col-md-3 control-label">等级</label>
                                        <div class="col-md-5">
                                            <select name="level" id="level" class="form-control" autofocus>
                                                <?php
                                                foreach (['一直使用阿里云','一个月内使用','三个月内使用','一年内使用','永远不使用'] as $key=>$val){
                                                    $level = $key + 1;
                                                    echo "<option value={$level}>$val</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="tag" class="col-md-3 control-label">当前视频源</label>
                                        <div class="col-md-5">
                                            <select name="current_type" id="current_type" class="form-control" autofocus>
                                                <?php
                                                foreach (['阿里云','优酷'] as $key=>$val){
                                                    $currentType = $key+1;
                                                    echo "<option value={$currentType}>$val</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="tag" class="col-md-3 control-label">视频封面</label>
                                        <div class="col-md-5">
                                            <input type="file" class="form-control" name="CoverURL" id="CoverURL"  autofocus>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="tag" class="col-md-3 control-label">视频文件</label>
                                        <div class="col-md-5">
                                            <input type="file" class="form-control" name="upfile" id="upfile"  autofocus>
                                        </div>
                                    </div>

                                    <div class="progress" style="display: none;">
                                        <div class="progress-bar" role="progressbar"
                                             aria-valuemin="0" aria-valuemax="100" style="width: 1%;">
                                            <span class="sr-only"></span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-md-7 col-md-offset-3">
                                            <button type="button" id="uploadButton" class="btn btn-primary btn-md">
                                                <i class="fa fa-plus-circle"></i>
                                                开始上传
                                            </button>
                                        </div>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop


