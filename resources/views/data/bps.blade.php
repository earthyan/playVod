@extends('admin.layouts.base')

@section('title','控制面板')

@section('pageHeader','控制面板')

@section('pageDesc','DashBoard')

@section('content')
    <head lang="en">
        <meta charset="UTF-8">
        <title></title>
        <link rel="stylesheet" href="{{asset('css/flow.css')}}">
        <style>
            *{
                padding: 0;
                margin: 0;
            }
        </style>
    </head>
    <body>
    <div class="show-con">
        <div class="date-con">
            <div class="date-select-txt">时间选择：</div>
            <div class="date-btn2" onclick="timeChange('today')">今天</div>
            <div class="date-btn" onclick="timeChange('yesterday')">昨天</div>
            <div class="date-btn" onclick="timeChange('week')">7天</div>
            <div class="date-btn" onclick="timeChange('month')">30天</div>
        </div>
        <div class="info-con">总流量：0.63 KB    (2018-05-21 00:00:00 至 2018-05-21 09:42:45)</div>
    </div>
    <div style="width: 100%;height: 400px">
        <canvas id="myChart" height="80"></canvas>
    </div>
    <script src="{{asset('js/jquery-1.11.0.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('js/Chart.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('js/service.js')}}" type="text/javascript"></script>
    <script src="{{asset('js/main.js')}}" type="text/javascript"></script>
    </body>
@stop
