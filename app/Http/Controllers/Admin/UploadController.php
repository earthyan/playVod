<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DefaultAcsClient;
use DefaultProfile;
use vod\Request\V20170321 as vod;

class UploadController extends Controller
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
    public function index(Request $request)
    {
        if ($request->ajax()) {
            try{
                $startTime = empty($request->startDate)? gmdate('Y-m-d\TH:i:s\Z',time()-7*86400) :gmdate('Y-m-d\TH:i:s\Z',strtotime($request->startDate));
                $endTime = empty($request->endDate)? gmdate('Y-m-d\TH:i:s\Z',time()) :gmdate('Y-m-d\TH:i:s\Z',strtotime($request->endDate));
                $VideoListRequest = new vod\GetVideoListRequest();
                date_default_timezone_set('UTC');
                $VideoListRequest->setStartTime($startTime);   // 视频创建的起始时间，为UTC格式
                $VideoListRequest->setEndTime($endTime);          // 视频创建的结束时间，为UTC格式
                $VideoListRequest->setPageNo(1);
                $VideoListRequest->setPageSize(10);
                $VideoListRequest->setAcceptFormat('JSON');
                $listRes = $this->client->getAcsResponse($VideoListRequest);
                $data = [];
                if (isset($listRes->VideoList->Video)) {
                    foreach ($listRes->VideoList->Video as $key => $val) {
                        $data[] = array(
                            'id' => $key + 1,
                            'Title' => $val->Title,
                            'CoverURL' => isset($val->CoverURL) ? $val->CoverURL : '',
                            'CreateTime' => $val->CreateTime,
                            'Duration' => date('i:s', $val->Duration),
                            'CateId' => isset($val->CateId)? $val->CateId : '',
                            'CateName' => isset($val->CateName)? $val->CateName : '未分类',
                            'VideoId' => $val->VideoId,
                            'state' => $this->getState($val->Status),
                        );
                    }
                }
                $ret = array(
                    'draw' => $request->get('draw'),
                    'data' => $data,
                    'recordsTotal' => $listRes->Total,
                    'recordsFiltered' => $listRes->Total,
                );
                return response()->json($ret);
            }catch (\Exception $e){
                $ret = array(
                    'draw' => $request->get('draw'),
                    'data' => [],
                    'recordsTotal' => 0,
                    'recordsFiltered' => 0,
                );
                return response()->json($ret);
            }

        }
        $startTime = date('Y-m-d H:i:s',strtotime('-7days'));
        $endTime = date('Y-m-d H:i:s');
        return view('admin.upload.index',compact('startTime','endTime'));
    }

    private function getState($status)
    {
        switch ($status) {
            case 'Uploading':
                $state = "上传中";
                break;
            case 'UploadSucc':
                $state = "上传中";
                break;
            case 'Transcoding':
                $state = "转码中";
                break;
            case 'TranscodeFail':
                $state = "转码失败";
                break;
            case 'Checking':
                $state = "审核中";
                break;
            case 'Blocked':
                $state = "屏蔽";
                break;
            case 'Normal':
                $state = "正常";
                break;
        }
        return $state;
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = [
            'Title' => '',
            'CoverURL' => '',
            'CreateTime' => '',
            'Duration' => '',
            'VideoId' => '',
            'state' => '',
        ];
        return view('admin.upload.create', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($VideoId)
    {
        try{
            $client = $this->client;
            $GetPlayInfoRequest = new vod\GetPlayInfoRequest();
            $GetPlayInfoRequest->setVideoId($VideoId);
            $playInfoRes = $client->getAcsResponse($GetPlayInfoRequest);
            $playInfo = $playInfoRes->VideoBase;
            $data = array(
                'Title' => $playInfo->Title,
                'CoverURL' => isset($playInfo->CoverURL) ? $playInfo->CoverURL : '',
                'CreateTime' => date('Y-m-d H:i:s',strtotime($playInfo->CreationTime)),
                'Duration' => date('i:s', $playInfo->Duration),
                'VideoId' => $playInfo->VideoId,
                'status'=>$playInfo->Status,
                'state' => $this->getState($playInfo->Status),
            );
            return view('admin.upload.edit', $data);
        }catch (\Exception $e){
            return redirect('/admin/upload')->withErrors('该视频状态不能编辑');
        }
    }


    /**
     * Update the specified resource in storage.
     *
     * @param
     * @param  int $VideoId
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$VideoId)
    {
        try{
            $Title = $request->title;
            $client = $this->client;
            $UpdatePlayInfoRequest = new vod\UpdateVideoInfoRequest();
            $UpdatePlayInfoRequest->setVideoId($VideoId);
            !empty($Title) && $UpdatePlayInfoRequest->setTitle($Title);
            $updateRes = $client->getAcsResponse($UpdatePlayInfoRequest);
            return redirect('/admin/upload')->withSuccess('修改成功');
        }catch (\Exception $e){
            return redirect('/admin/upload')->withErrors($e->getMessage());
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int $VideoId
     * @return \Illuminate\Http\Response
     */
    public function destroy($VideoId)
    {
        try{
            $client = $this->client;
            $DeleteRequest = new vod\DeleteVideoRequest();
            $DeleteRequest->setVideoIds($VideoId);
            $deleteRes = $client->getAcsResponse($DeleteRequest);
            return redirect('/admin/upload')->withSuccess('删除成功');
        }catch (\Exception $e){
            return redirect('/admin/upload')->withErrors($e->getMessage());
        }
    }





}
