/**
 * Created by ajex on 2018/6/11.
 */
var service = service || {
    // domain:"http://192.168.110.233/samba/hainan/dhvod/public/index.php/api/flow",
    domain:"https://dhvod.17m3.com/api/flow",
    getFlow:function (startTime,endTime,Interval,callback)
    {
        $.ajax({
            type:'get',
            url: service.domain,
            dataType:'json',
            data:{"math":Math.random()*100000,"startTime":startTime,"endTime":endTime,"Interval":Interval},
            success:function (data)
            {
                callback.call(this,data);
            }
        })
    }
}