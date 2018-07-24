/**
 * Created by ajex on 2018/4/25.
 */
(function (){
//    var _data;
//    var _vid;
//    var _PlayAuth
//    var _callBack;
//    var _serviceData;
//    var _this;
    var youkuHandler = function ()
    {
//        <iframe src='http://player.youku.com/embed/XMzU2Nzg2MjQ0OA==' frameborder=0 'allowfullscreen'></iframe>
        this.vid = this.serviceData.resp.key.youku_id;
        this.data.vid = this.vid;
        $('#' + this.data.id).html("<iframe id='youkuPlayer'  style='width: " + this.data.width + ";height: " + this.data.height + "' src='http://player.youku.com/embed/" +  this.data.vid + "' frameborder=0 'allowfullscreen'></iframe>");
        this.callBack.call(this,$('#youkuPlayer'));
    }
    var aliHandler = function ()
    {
        if(this.data.soulType == "youku")
        {
            this.youkuHandler();
            return
        }
        var _this = this;
        aliFileLoad(function (){
            _this.vid = _this.serviceData.resp.key.aliyun_id;
            _this.data.vid = _this.vid;
            $.get("https://dhvod.17m3.com/api/playAuth?VideoId=" + _this.vid,function (data){
                var _playData
                if( typeof(data)=="object" ){
                    _playData = data;
                }
                else
                {
                    _playData = $.parseJSON(data);
                }

                if(data.code == 200)
                {

                    _this.PlayAuth = _playData.resp.PlayAuth;
                    _this.data.playauth = _this.PlayAuth;
                }
                else
                {
                    alert(data.msg)
                    return;
                }
                var player = new Aliplayer(_this.data,_this.callBack);
                _this.player = player;
//                _this.emit("AliPlayer_Init",{"player":player})
            });
        })

    }
    var aliFileLoad = function (callBack)
    {
// $.include(['https://g.alicdn.com/de/prismplayer/2.6.0/skins/default/aliplayer-min.css',"https://g.alicdn.com/de/prismplayer/2.6.0/aliplayer-min.js"]);
        $("head").append("<link>");
       var css = $("head").children(":last");
        css.attr({
            rel: "stylesheet",
            type: "text/css",
            href: "https://g.alicdn.com/de/prismplayer/2.6.0/skins/default/aliplayer-min.css"
        });
        $.getScript("https://g.alicdn.com/de/prismplayer/2.6.0/aliplayer-min.js",callBack);
    }
    window.SoulPlayer = function SoulPlayer(data,callBack)
    {
//        if(!this.handles){//ie8及以下不支持
//            //this.handles={};
//            Object.defineProperty(this, "handles", {
//                value: {},
//                enumerable: false,
//                configurable: true,
//                writable: true
//            })
//        }
        this.data = data
        this.callBack = callBack;
        var _this = this;
        $.get("https://dhvod.17m3.com/api/transmit/" + data.vid,function (data){
            if( typeof(data)=="object" ){
                _this.serviceData = data;
            }
            else
            {
                _this.serviceData = $.parseJSON(data);
            }
            if(_this.serviceData.code == 200)
            {
                if(_this.serviceData.resp.type == 1)
                {
                    _this.aliHandler()
                }
                else if(this.serviceData.resp.type == 2)
                {
                    _this.youkuHandler()
                }
                else
                {
                    alert(_this.serviceData.msg)
                }
            }
            else
            {
                alert(_this.serviceData.msg)
            }
        });
        window.SoulPlayer.prototype.youkuHandler = youkuHandler;
        window.SoulPlayer.prototype.aliHandler = aliHandler;
        window.SoulPlayer.prototype.aliFileLoad = aliFileLoad;
//        this.on = on;
//        this.off = off;
//        this.emit = emit;
    }
//     var on = function (eventName, callback) {
//        //你的代码
//        if(!_this.handles[eventName]){
//            _this.handles[eventName]=[];
//        }
//         _this.handles[eventName].push(callback);
//    }
//    var off = function (eventName, callback)
//    {
//        if(!_this.handles[eventName])
//        {
//            return;
//        }
//        for(var i = 0; i < _this.handles[eventName].length;i++)
//        {
//            if( _this.handles[eventName][i] == callback)
//            {
//                _this.handles[eventName].splice(i,1);
//                return;
//            }
//        }
//    }
//    // 触发事件 eventName
//     var emit = function (eventName) {
//        //你的代码
//        if(_this.handles[arguments[0]]){
//            for(var i=0;i<_this.handles[arguments[0]].length;i++){
//                _this.handles[arguments[0]][i](arguments[1]);
//            }
//        }
//    }
}())
