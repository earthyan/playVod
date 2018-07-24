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
                            <h3 class="panel-title">编辑视频</h3>
                        </div>
                        <div class="panel-body">

                            @include('admin.partials.errors')
                            @include('admin.partials.success')
                            <form class="form-horizontal" role="form" method="POST"
                                  action="{{url("/admin/upload/$VideoId")}}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="_method" value="PUT">
                                <input type="hidden" name="id" value="{{ $VideoId }}">
                                <input type="hidden" name="status" value="{{ $status }}">

                                <div class="form-group">
                                    <label for="tag" class="col-md-3 control-label">视频标题</label>
                                    <div class="col-md-5">
                                        <input type="text" class="form-control" name="title" id="title" value="{{ $Title }}" autofocus>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="tag" class="col-md-3 control-label">视频封面</label>
                                    <div class="col-md-5">
                                        <img src="{{$CoverURL}}" height="100" width="150" alt="" name="CoverURL" id="CoverURL">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="tag" class="col-md-3 control-label">视频ID</label>
                                    <div class="col-md-5">
                                        <input type="text" class="form-control" name="VideoId" id="VideoId" value="{{ $VideoId }}" disabled autofocus>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="tag" class="col-md-3 control-label">视频时长</label>
                                    <div class="col-md-5">
                                        <input type="text" class="form-control" name="Duration" id="Duration" value="{{ $Duration }}" disabled autofocus>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="tag" class="col-md-3 control-label">创建时间</label>
                                    <div class="col-md-5">
                                        <input type="text" class="form-control" name="CreateTime" id="CreateTime" value="{{ $CreateTime }}" disabled autofocus>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-7 col-md-offset-3">
                                        <button type="submit" class="btn btn-primary btn-md">
                                            <i class="fa fa-plus-circle"></i>
                                            保存
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
@stop