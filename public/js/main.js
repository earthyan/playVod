/**
 * Created by ajex on 2018/6/11.
 */
var chartData;
var startDate;
var endDate;
var curDate;
var timeList = [];
var dataList = [];
var timeType = "today";//today yesterday week month
var titleLabel = "流量"
function timeChange(type,index)
{
    changeBtnStyle(index);
    timeType = type;
    var typeTimeObj = {"today":60*60,"yesterday":60*60,"week":60*60,"month":24*60*60}
    var curTime;
    var endTime;//用来除去计算中可能的小数点
    var startTime;
    if(type == "today")
    {
        curTime = new Date();
        endTime = new Date(curTime.getFullYear(),curTime.getMonth(),curTime.getDate(),curTime.getHours(),0,0);
        startTime = new Date(curTime.getFullYear(),curTime.getMonth(),curTime.getDate(),0,0,0);
    }
    else if(type == "yesterday")
    {
        var d = new Date();
        endTime = new Date(d.getFullYear(), d.getMonth(), d.getDate(),24,0,0);
        startTime =new Date(d.getFullYear(), d.getMonth(), d.getDate(),0,0,0);
    }
    else if(type == "week")
    {
        curTime = new Date();
        endTime = new Date(curTime.getFullYear(),curTime.getMonth(),curTime.getDate(),curTime.getHours(),0,0);
        startTime = DateAdd("d",-7,endTime);
    }
    else if(type == "month")
    {
        curTime = new Date();
        endTime = new Date(curTime.getFullYear(),curTime.getMonth(),curTime.getDate(),24,0,0);
        startTime = DateAdd("d",-30,endTime);
    }

    console.log(DateToStr(startTime),DateToStr(endTime))
    service.getFlow(DateToStr(startTime),DateToStr(endTime),typeTimeObj[type],function (data){
        console.log(data);
        setData(data);
    })
}
function changeBtnStyle(type)
{
    for(var i = 1;i <= 4;i++)
    {
        $('#btn' + i).removeClass("date-btn2");
        $('#btn' + i).removeClass("date-btn");
        $('#btn' + i).addClass("date-btn");
    }
    $('#btn' + type).addClass("date-btn2");
}
timeChange('today',1);
function scaleUnit(value,len)//len表示到达多少位时开始换算
{
    var obj = {};
    value = Math.round(value);
    if(value.toString().length>(len + 9))
    {
        obj.value =  Math.round(parseFloat(value/1024/1024/1024/1024) * 100) / 100;
        obj.unit = "TB";
        obj.childUnit = "GB"
        obj.chu = 1024*1024*1024;
    }
    else if(value.toString().length>(len + 6))
    {
        obj.value =  Math.round(parseFloat(value/1024/1024/1024) * 100) / 100;
        obj.unit = "GB";
        obj.childUnit = "MB"
        obj.chu = 1024*1024;
    }
    else if(value.toString().length>(len+3))
    {
        obj.value =  Math.round(parseFloat(value/1024/1024) * 100) / 100;
        obj.unit = "MB";
        obj.childUnit = "KB"
        obj.chu = 1024;
    }
    else if(value.toString().length>len)
    {
        obj.value =  Math.round(parseFloat(value/1024) * 100) / 100;
        obj.unit = "KB";
        obj.childUnit = "B"
        obj.chu = 1;
    }
    else if(value == 0)
    {
        obj.value = 0;
        obj.unit = "KB";
        obj.childUnit = "B"
        obj.chu = 1;
    }
    return obj;
}
function setData(data)
{
   var obj = scaleUnit(data.total,1);
    var chu = obj.chu;
    $('.info-con').text("总" + titleLabel + "：" + obj.value +  " " + obj.unit + "    (" + data.startTime + " 至 " + data.endTime + ")");

    chartData = data;
    startDate = new Date(chartData.startTime);
    endDate = new Date(chartData.endTime);
    curDate  = new Date();
    timeList = [];
    dataList = [];
    if(timeType == "today")
    {
        var length = curDate.getHours();
        for(var i=0;i <= length;i++)
        {
            var str = i.toString() + ":00";
            timeList.push(str)
        }
        for(var i=0;i<=length;i++)
        {
            dataList.push(0);
        }
        for(var i=0;i < chartData.data.length;i++)
        {
            var time = new Date(chartData.data[i].TimeStamp);
            var hour = time.getHours();
            dataList[hour] = Math.round(chartData.data[i].Value/chu);
        }
    }
    else if(timeType == "yesterday")
    {
        for(var i=0;i <= 24;i++)
        {
            var str = i.toString() + ":00";
            timeList.push(str)
        }
        for(var i=0;i<=24;i++)
        {
            dataList.push(0);
        }
        for(var i=0;i < chartData.data.length;i++)
        {
            var time = new Date(chartData.data[i].TimeStamp);
            var hour = time.getHours();
            dataList[hour] = Math.round(chartData.data[i].Value/chu);
        }
    }
    else if(timeType == "week")
    {
        // var weekStartDate;
        var weekEndDate;
        var list = [];
        for(var i=0;i < 168;i++)
        {
            list.push(0);
        }
        console.log(endDate.getFullYear())
        if(endDate.getHours()>=12)
        {
            weekEndDate = new Date(endDate.getFullYear(),endDate.getMonth(),endDate.getDate(),12,0,0);
            for(var i=6;i >= 0;i--)
            {
                var date =  DateAdd("d",-i,endDate);
                var str = (date.getMonth() + 1) + "-" + date.getDate();
                timeList.push(str)
                timeList.push("12:00");
            }
        }
        else
        {
            weekEndDate = new Date(endDate.getFullYear(),endDate.getMonth(),endDate.getDate(),0,0,0);
            for(var i=6;i >= 0;i--)
            {
                var date =  DateAdd("d",-i,endDate);
                var str = (date.getMonth() + 1) + "-" + date.getDate();
                timeList.push("12:00");
                timeList.push(str)
            }
        }

        for(var i=0;i < chartData.data.length;i++)
        {
            var date = new Date(chartData.data[i].TimeStamp);
            var num = weekEndDate.getTime() - date.getTime();
            var index = 168 - num/(1000*60*60);
            list[index-1] =  Math.round(chartData.data[i].Value/chu);//下标需要-1
        }
        var len = list.length/timeList.length;
        for(var i=0;i<timeList.length;i++)
        {
            var a = 0;
            for(var j=i*len;j<(i+1)*len;j++)
            {
                a+=parseInt(list[j])
            }
            dataList.push(a)
        }
    }
    else if(timeType == "month")
    {
        var monthEndDate;
        var list = [];
        for(var i=29;i > 0;i-=2)
        {
            var date =  DateAdd("d",-i,endDate);
            var str = (date.getMonth() + 1) + "-" + date.getDate();
            timeList.push(str)
        }
        for(var i=0;i<30;i++)
        {
            list.push(0);
        }
        monthEndDate = new Date(endDate.getFullYear(),endDate.getMonth(),endDate.getDate(),24,0,0);
        for(var i=0;i < chartData.data.length;i++)
        {
            var listDate = new Date(chartData.data[i].TimeStamp);

            var date = new Date();
            var num = monthEndDate
                .getTime() - date.getTime();
            var index = 30 - num/(1000*60*60*24);
            list[index-1] =  Math.round(chartData.data[i].Value/chu);;//下标需要-1
        }
        var len = list.length/timeList.length;
        for(var i=0;i<timeList.length;i++)
        {
            var a = 0;
            for(var j=i*len;j<(i+1)*len;j++)
            {
                a+=parseInt(list[j])
            }
            dataList.push(a)
        }
    }
    if(!myChart)
    {
        ctx = document.getElementById("myChart");
        myChart = new Chart(ctx, {
            type: 'line',
            devicePixelRatio:0.5,
            data: {
                labels: timeList,
                datasets: [{
                    label: titleLabel + "(单位 " + obj.childUnit + " )",
                    data: dataList,
                    backgroundColor: [
                        'rgba(0,0,255,0)'
                    ],
                    borderColor: [
                        'rgba(0,0,255,1)'
                    ],
                    borderWidth: 0.5,
                    steppedLine:false
                }]
            },
            options: {
                tooltips: {
                    mode: 'nearest'
                }
            }
        });
    }
    else
    {
        myChart.data =  {
            labels: timeList,
            datasets: [{
                label: titleLabel + "(单位 " + obj.childUnit + " )",
                data: dataList,
                backgroundColor: [
                    'rgba(0,0,255,0)'
                ],
                borderColor: [
                    'rgba(0,0,255,1)'
                ],
                borderWidth: 0.5,
                steppedLine:false
            }]
        }
        myChart.update();
    }
}


var ctx;
var myChart;
function DateAdd(interval, number, date) {
    var time;
    switch (interval) {
        case "y": {
            time = number*365*24*60*60*1000;
            break;
        }
        case "m": {
            time = number*30*24*60*60*1000;
            break;
        }
        case "w": {
            time = number*7*24*60*60*1000;
            break;
        }
        case "d": {
            time = number*24*60*60*1000;
            break;
        }
        case "h": {
            time = number*60*60*1000;
            break;
        }
        case "m": {
            time = number*60*1000;
            break;
        }
        case "s": {
            time = number*1000;
            break;
        }
        default: {
            time = number*24*60*60*1000;
            break;
        }
    }
    var curTime = date.getTime();
    return new Date(curTime + time);
}
function add0(num)
{
    var str = num.toString();
    str = str.length>1?str:"0"+str;
    return str;
}
function DateToStr(date)
{
    return date.getFullYear() + "-" + add0(date.getMonth()+1) + "-" + add0(date.getDate()) + " " + add0(date.getHours()) + ":" + add0(date.getMinutes());
}