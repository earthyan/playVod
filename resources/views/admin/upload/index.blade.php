@extends('admin.layouts.base')

@section('title','控制面板')

@section('pageHeader','控制面板')

@section('pageDesc','DashBoard')

@section('content')
    <div class="row page-title-row" style="margin:5px;">
        <div class="col-md-6 text-left">
            @if(Gate::forUser(auth()->user())->check('admin.upload.create'))
                <a href="{{ url('admin/upload/create') }}" class="btn btn-success btn-md">
                    <i class="fa fa-plus-circle"></i> 上传视频
                </a>
            @endif
        </div>
        <div class="col-md-6">
            开始日期：<input type="text" class="Wdate" id="startDate" value="{{$startTime}}">
            结束日期：<input type="text" class="Wdate" id="endDate" value="{{$endTime}}">
            <button id="search">查询</button>
        </div>
    </div>
    <div class="row page-title-row" style="margin:5px;">
        <div class="col-md-6">
        </div>
        <div class="col-md-6 text-right">
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="box">
                @include('admin.partials.errors')
                @include('admin.partials.success')
                <div class="box-body">
                    <table id="tags-table" class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th data-sortable="false" class="hidden-sm"></th>
                            <th class="hidden-sm">视频名称</th>
                            <th class="hidden-sm">封面</th>
                            <th class="hidden-sm">视频ID</th>
                            <th class="hidden-sm">视频时长</th>
                            <th class="hidden-sm">视频分类</th>
                            <th class="hidden-sm">视频状态（全部）</th>
                            <th class="hidden-sm">创建时间</th>
                            <th data-sortable="false">操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>
    <div class="modal fade" id="modal-delete" tabIndex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">
                        ×
                    </button>
                    <h4 class="modal-title">提示</h4>
                </div>
                <div class="modal-body">
                    <p class="lead">
                        <i class="fa fa-question-circle fa-lg"></i>
                        确认要删除这个视频吗?
                    </p>
                </div>
                <div class="modal-footer">
                    <form class="deleteForm" method="POST" action="{{url('/admin/video')}}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                        <button type="submit" class="btn btn-danger">
                            <i class="fa fa-times-circle"></i>确认
                        </button>
                    </form>
                </div>
            </div>
            @stop

            @section('js')
                <script>
                    $(function () {
                        var table = $("#tags-table").DataTable({
                            language: {
                                "sProcessing": "处理中...",
                                "sLengthMenu": "显示 _MENU_ 项结果",
                                "sZeroRecords": "没有匹配结果",
                                "sInfo": "显示第 _START_ 至 _END_ 项结果，共 _TOTAL_ 项",
                                "sInfoEmpty": "显示第 0 至 0 项结果，共 0 项",
                                "sInfoFiltered": "(由 _MAX_ 项结果过滤)",
                                "sInfoPostFix": "",
                                "sSearch": "搜索:",
                                "sUrl": "",
                                "sEmptyTable": "表中数据为空",
                                "sLoadingRecords": "载入中...",
                                "sInfoThousands": ",",
                                "oPaginate": {
                                    "sFirst": "首页",
                                    "sPrevious": "上页",
                                    "sNext": "下页",
                                    "sLast": "末页"
                                },
                                "oAria": {
                                    "sSortAscending": ": 以升序排列此列",
                                    "sSortDescending": ": 以降序排列此列"
                                }
                            },
                            searching: false, //去掉搜索框
                            order: [[1, "desc"]],
                            serverSide: true,
                            ajax: {
                                url: "{{ url('admin/upload/index') }}",
                                type: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                                },
                            },
                            "columns": [
                                {"data": "id"},
                                {"data": "Title"},
                                {"data": "CoverURL"},
                                {"data": "VideoId"},
                                {"data": "Duration"},
                                {"data": "CateName"},
                                {"data": "state"},
                                {"data": "CreateTime"},
                                {"data": "action"}
                            ],
                            columnDefs: [
                                {
                                    'targets': -1, "render": function (data, type, row) {
                                        var row_edit = {{Gate::forUser(auth()->user())->check('admin.upload.edit') ? 1 : 0}};
                                        var row_delete = {{Gate::forUser(auth()->user())->check('admin.upload.destroy') ? 1 :0}};
                                        var str = '';

                                        //编辑
                                        if (row_edit) {
                                            str += '<a style="margin:3px;" href="{{url('/admin/upload/')}}' + '/' + row['VideoId'] + '/edit' + '" class="X-Small btn-xs text-success "><i class="fa fa-edit"></i> 编辑</a>';
                                        }

                                        //删除
                                        if (row_delete) {
                                            str += '<a style="margin:3px;" href="#" attr="' + row['VideoId'] + '" class="delBtn X-Small btn-xs text-danger"><i class="fa fa-times-circle"></i> 删除</a>';
                                        }
                                        return str;
                                    }
                                },
                                {
                                    'targets': 2, "render": function (data, type, row) {
                                        if (data) {
                                            return "<img src='" + data + "' height='100' width='150'/>";
                                        } else {
                                            return data;
                                        }
                                    }
                                }
                            ]
                        });
                        table.on('preXhr.dt', function () {
                            loadShow();
                        });
                        table.on('draw.dt', function () {
                            loadFadeOut();
                        });
                        table.on('order.dt search.dt', function () {
                            table.column(0, {search: 'applied', order: 'applied'}).nodes().each(function (cell, i) {
                                cell.innerHTML = i + 1;
                            });
                        }).draw();
                        $("table").delegate('.delBtn', 'click', function () {
                            var id = $(this).attr('attr');
                            $('.deleteForm').attr('action', "{{url('/admin/upload/')}}" + '/' + id);
                            $("#modal-delete").modal();
                        });

                        //时间筛选
                        $('#startDate').on('click', function(){
                            WdatePicker({
                                dateFmt:"yyyy-MM-dd HH:mm:ss",
                                maxDate:'#F{$dp.$D(\'endDate\')||\'%y-%M-%d\'}'
                            });
                        });

                        $('#endDate').on('click', function(){
                            WdatePicker({
                                dateFmt:"yyyy-MM-dd HH:mm:ss",
                                maxDate:'#F{\'%y-%M-%d\'}',
                                minDate:'#F{$dp.$D(\'startDate\')}'
                            });
                        });

                        $('#search').on('click',function () {
                           var startDate = $('#startDate').val();
                           var endDate = $('#endDate').val();
                           var param = {
                               "startDate":startDate,
                               "endDate":endDate,
                               "_token": "{{ csrf_token() }}"
                           };
                           console.log(param);
                            table.settings()[0].ajax.data = param;
                            table.ajax.reload();
                        });
                    });

                </script>
@stop

