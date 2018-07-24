<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DefaultAcsClient;
use DefaultProfile;
use vod\Request\V20170321 as vod;

class DataController extends Controller
{
    protected $client;

    public function __construct()
    {
        $AccessKeyId = \Config::get('constants.VOD_ACCESS_ID');
        $AccessKeySecret = \Config::get('constants.VOD_ACCESS_SCERET');
        $regionId = 'cn-shanghai';
        $profile = DefaultProfile::getProfile($regionId, $AccessKeyId, $AccessKeySecret);
        $this->client = new DefaultAcsClient($profile);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function flow(Request $request)
    {
        try {
            $req_startTime = $request->startTime;
            $req_endTime = $request->endTime;
            $req_interval = $request->Interval;
//            $req_startTime = "2018-05-11";
//            $req_endTime = "2018-06-11";
//            $req_interval = "86400";
            //以北京时间（UTC+8）为基准，每天上午9点生成前一天的播放数据统计
            $startTime = empty($req_startTime) ? date('Y-m-d\TH:i\Z', time() - 86400) : date('Y-m-d\TH:i\Z', strtotime($req_startTime));
            $endTime = empty($req_endTime) ? date('Y-m-d\TH:i\Z', time()) : date('Y-m-d\TH:i\Z', strtotime($req_endTime));
            $Interval = empty($req_interval) ? '3600' : $req_interval;
            $client = $this->client;
            $FlowRequest = new vod\DescribeDomainFlowDataRequest();
            $FlowRequest->setStartTime($startTime);
            $FlowRequest->setEndTime($endTime);
            //查询数据的时间粒度，支持300, 3600, 14400, 28800和86400秒 默认300
            $FlowRequest->setInterval($Interval);
            $flowRes = $client->getAcsResponse($FlowRequest);
            $DataModule = $flowRes->FlowDataPerInterval->DataModule;
            $total = 0;
            foreach ($DataModule as $value) {
                $total += $value->Value;
                $value->TimeStamp = date('Y-m-d H:i:s', strtotime($value->TimeStamp));
            }
            $result = array(
                'startTime' => $req_startTime,
                'endTime' => $req_endTime,
                'total' => $total,
                'data' => $DataModule
            );
            return response()->json($result);
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }


    public function top(Request $request)
    {
        try{
            //以北京时间（UTC+8）为基准，每天上午9点生成前一天的播放数据统计
            $req_bizDate = $request->bizDate;
            $bizDate = empty($req_bizDate)? date('Y-m-d\TH:i:s\Z',time()-86400) :date('Y-m-d\TH:i:s\Z',strtotime($req_bizDate));
            $client = $this->client;
            $TopVideoRequest = new vod\DescribePlayTopVideosRequest();
            $TopVideoRequest->setBizDate($bizDate);
            $topVideoRes = $client->getAcsResponse($TopVideoRequest);
            return response()->json($topVideoRes);
        } catch (\Exception $e) {
            echo $e->getMessage();
        }

    }

    public function total(Request $request)
    {
        try{
            //以北京时间（UTC+8）为基准，每天上午9点生成前一天的播放数据统计
            $req_startTime = $request->startTime;
            $req_endTime = $request->endTime;
            $startTime = empty($req_startTime)? date('Y-m-d\TH:i\Z',time()-7*86400) :date('Y-m-d\TH:i\Z',strtotime($req_startTime));
            $endTime = empty($req_endTime)? date('Y-m-d\TH:i\Z',time()) :date('Y-m-d\TH:i\Z',strtotime($req_endTime));
            $client = $this->client;
            $PlayTotalRequest = new vod\DescribePlayUserTotalRequest();
            $PlayTotalRequest->setStartTime($startTime);
            $PlayTotalRequest->setEndTime($endTime);
            $playTotalRes = $client->getAcsResponse($PlayTotalRequest);
            return response()->json($playTotalRes);
        }catch (\Exception $e){
            echo $e->getMessage();
        }

    }


    public function bps(Request $request)
    {
        try{
            $req_startTime = $request->startTime;
            $req_endTime = $request->endTime;
            $req_interval = $request->Interval;

//            $req_startTime = date('Y-m-d H:i:s',strtotime('-7day'));
//            $req_endTime = date('Y-m-d H:i:s');
//            $req_interval = '86400';
            //以北京时间（UTC+8）为基准，每天上午9点生成前一天的播放数据统计
            $startTime = empty($req_startTime)? date('Y-m-d\TH:i\Z',time()-86400) :date('Y-m-d\TH:i\Z',strtotime($req_startTime));
            $endTime = empty($req_endTime)? date('Y-m-d\TH:i\Z',time()) :date('Y-m-d\TH:i\Z',strtotime($req_endTime));
            $Interval = empty($req_interval)? '3600' :$req_interval;
            $client = $this->client;
            $FlowRequest = new vod\DescribeDomainBpsDataRequest();
            $FlowRequest->setStartTime($startTime);
            $FlowRequest->setEndTime($endTime);
            //查询数据的时间粒度，支持300, 3600, 14400, 28800和86400秒 默认300
            $FlowRequest->setInterval($Interval);
            $flowRes = $client->getAcsResponse($FlowRequest);
            $DataModule = $flowRes->FlowDataPerInterval->DataModule;
            $total = 0;
            foreach ($DataModule as $value){
                $total += $value->Value;
                $value->TimeStamp = date('Y-m-d H:i:s',strtotime($value->TimeStamp)+8*3600);
            }
            $result = array(
                'startTime'=>$req_startTime,
                'endTime'=>$req_endTime,
                'total'=>$total,
                'data'=>$DataModule
            );
            return  response()->json($result);
        }catch (\Exception $e){
            echo $e->getMessage();
        }

    }


    public function avg(Request $request)
    {
        try{
            //以北京时间（UTC+8）为基准，每天上午9点生成前一天的播放数据统计
            $req_startTime = $request->startTime;
            $req_endTime = $request->endTime;

            $startTime = empty($req_startTime)? date('Y-m-d\TH:i:s\Z',time()-7*86400) :date('Y-m-d\TH:i:s\Z',strtotime($req_startTime));
            $endTime = empty($req_endTime)? date('Y-m-d\TH:i:s\Z',time()) :date('Y-m-d\TH:i:s\Z',strtotime($req_endTime));
            $client = $this->client;
            $PlayAvgRequest = new vod\DescribePlayUserAvgRequest();
            $PlayAvgRequest->setStartTime($startTime);
            $PlayAvgRequest->setEndTime($endTime);
            $playAvgRes = $client->getAcsResponse($PlayAvgRequest);
            return response()->json($playAvgRes);
        }catch (\Exception $e){
            return redirect('/admin/avg')->withErrors('暂时没有播放均量数据');
        }

    }

}
