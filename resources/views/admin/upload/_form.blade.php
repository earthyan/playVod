<script type="text/javascript">
    $(document).ready(function () {
        var upinfo; //上传视频凭证变量
        var title; //视频标题
        var VideoId;//阿里云视频ID
        var uploader = new AliyunUpload.Vod({
            'onUploadFailed': function (uploadInfo, code, message) {
                console.log('上传失败');
            },
            'onUploadstarted': function (uploadInfo) {
                //拿到凭证开始上传
                uploader.setUploadAuthAndAddress(uploadInfo, upinfo.UploadAuth, upinfo.UploadAddress);
                console.log("onUploadStarted:" + uploadInfo.file.name + ", endpoint:" + uploadInfo.endpoint + ", bucket:" + uploadInfo.bucket + ", object:" + uploadInfo.object);
            },
            'onUploadProgress': function (uploadInfo, totalSize, uploadedSize) {
                //上传进度条处理
                $('.progress-bar').css({'width': Math.ceil(uploadedSize * 100) + "%"});
                console.log("onUploadProgress:file:" + uploadInfo.file.name + ", fileSize:" + totalSize  + ", percent:" + Math.ceil(uploadedSize*100) + "%");
            },
            'onUploadSucceed': function (uploadInfo) {
                $('#videoid').val(upinfo.VideoId);
                $('#filesize').val(uploadInfo.file.size);
                console.log("提示", "上传成功,请等待服务器转码");
                console.log("onUploadSucceed: " + uploadInfo.file.name + ", endpoint:" + uploadInfo.endpoint + ", bucket:" + uploadInfo.bucket + ", object:" + uploadInfo.object);
                console.log(uploadInfo);
                alert('视频上传成功');
                storeVideo();//存储视频
                {{--window.location.href = "{{url('admin/upload/index')}}"--}}
            },
            'onUploadTokenExpired': function () {
                //刷新凭证
                console.log("onUploadTokenExpired");
                refreshuploadinfo();
                uploader.resumeUploadWithAuth(upinfo.UploadAuth);
            },

        });

        //拿上传凭证
        function getuploadinfo(obj) {
            if (!$('#title').val()) {
                title = obj.files[0].name;
            }else{
                title = $('#title').val();
            }
            var fm = new FormData();
            fm.append('title', title);
            fm.append('filename', obj.files[0].name);
            fm.append('filesize', obj.files[0].size);
            fm.append('CoverURL', document.getElementById('CoverURL').files[0]);
            console.log(fm);

            $.ajax({
                async: false,
                type: 'post',
                dataType: 'json',
                url: '{{url('/api/CreateUploadVideo')}}',
                data: fm,
                cache: false,
                processData: false,
                contentType: false,
                error: function () {
                    console.log('获取上传凭证失败');
                },
                success: function (res) {
                    upinfo = res.resp;
                    console.log(upinfo);
                    VideoId = res.resp.VideoId;
                }
            });
        }

        function storeVideo() {
            CoverURL = $('#CoverURL').val();
            level = $('#level').val();
            current_type = $('#current_type').val();
            youku_id = $('#youku_id').val();
            $.ajax({
                async: false,
                type: 'get',
                dataType: 'json',
                url: '{{url('/api/storeVideo')}}',
                data: {title: title,youku_id:youku_id,aliyun_id: VideoId, level: level,current_type:current_type},
                error: function () {
                    console.log('存储视频失败');
                },
                success: function (res) {
                    console.log(res);
                }
            });
        }

        function refreshuploadinfo() {
            $.ajax({
                async: false,
                type: 'post',
                dataType: 'json',
                url: '{{url('/api/RefreshUploadVideo')}}',
                data: {VideoId: upinfo.VideoId},
                error: function () {
                    console.log('刷新凭证失败');
                },
                success: function (res) {
                    upinfo = res.resp;
                    console.log(upinfo);
                }
            });
        }
        $('#upfile').on('change', function () {
            getuploadinfo(this);
            if (upinfo) {
                uploader.init();
                var userData = '{"Vod":{"UserData":{"IsShowWaterMark":"false","Priority":"7"}}}';
                uploader.addFile(this.files[0], null, null, null, userData);
                // $('.progress').show();
                // uploader.startUpload();
            } else {
                console.log('先获取上传凭证')
            }
        });

        $('#uploadButton').on('click',function () {
            if($(this).hasClass('disabled')){
                return false;
            }
            console.log("start upload.");
            $(this).addClass('disabled');
            $('.progress').show();

            uploader.startUpload();
        });


    });
</script>
<script src="{{asset('js/es6-promise.min.js')}}"></script>
<script src="{{asset('js/aliyun-upload-sdk1.3.1.min.js')}}"></script>
<script src="{{asset('js/aliyun-oss-sdk4.13.2.min.js')}}"></script>