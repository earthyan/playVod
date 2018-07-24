<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title></title>
    <style>
        *{
            margin: 0;
            padding: 0;
        }
    </style>
</head>
<body>
<div  class="prism-player" id="playCon"  style="position: absolute;"></div>
<!--必须提前引入jquery-->
<script src="{{asset('plugins/jQuery/jQuery-2.1.4.min.js')}}" type="text/javascript"></script>
<script src="{{asset('SoulPlayer.js')}}" type="text/javascript"></script>
<script>
    var soulPlayer = new SoulPlayer({
        id: 'playCon',
        width: '1200px',
        height:'800px',
        autoplay: true,
//        soulType:"youku",
        vid :"fb3dd4c7fc0a483bbe4c4038e54d48df"
    },function(player){
        //soulPlayer并非真正的播放器，而是阿里云和优酷的选择器，
        //这边的player才是真正的阿里云播放器 如果需要高级用法，
        // 需要引用并操作这里的player，高级用法只有阿里云有权限
        // 如果没有阿里云播放权限 这里会返回youku的iframe
    });
</script>
</body>
</html>