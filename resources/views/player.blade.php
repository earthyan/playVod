<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=750,user-scalable=no,target-densitydpi=device-dpi" />
    <title>SoulPlayer</title>
    <style>
        *{
            margin: 0;
            padding: 0;
        }
    </style>
</head>
<body style="overflow: hidden">
<div  class="prism-player" id="playCon"  style="position: absolute;width: 100%;height: 100%"></div>
<!--必须提前引入jquery-->
<script src="{{asset('plugins/jQuery/jQuery-2.1.4.min.js')}}" type="text/javascript"></script>
<script src="{{asset('SoulPlayer.js')}}" type="text/javascript"></script>
<script>
    function getRequest() {
        var url = window.location.search; //获取url中"?"符后的字串
        var theRequest = new Object();
        if (url.indexOf("?") != -1) {
            var str = url.substr(1);
            var strs = str.split("&");
            for(var i = 0; i < strs.length; i ++) {

                theRequest[strs[i].split("=")[0]]=decodeURI(strs[i].split("=")[1]);

            }
        }
        return theRequest;
    }
    var urlRequest= getRequest();
    var vidStr = urlRequest.vid;
    var auto = urlRequest.autoplay;
    if(vidStr)
    {
        var soulPlayer = new SoulPlayer({
            id: 'playCon',
            autoplay: auto,
            vid :vidStr
        },function(player){

        });
    }
    else
    {
        alert("vid不正确")
    }
</script>
</body>
</html>