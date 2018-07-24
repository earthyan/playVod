<?php
namespace App\Http\Controllers\Api;

use App\Http\Services\VodService;
use DefaultAcsClient;
use DefaultProfile;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use vod\Request\V20170321 as vod;
use OSS\OssClient;
use OSS\Core\OssException;

class VodController extends Controller
{
    const SERVICE_IP_WHITELIST = [
        '192.168.110.233',
        '115.231.110.183',
        '122.224.197.37',
    ];

    protected $service;
    protected $client;
    protected $ossClient;
    private $vodService;

    public function __construct(VodService $vodService)
    {
        $AccessKeyId = \Config::get('constants.VOD_ACCESS_ID');
        $AccessKeySecret = \Config::get('constants.VOD_ACCESS_SCERET');
        $regionId = 'cn-shanghai';
        $profile = DefaultProfile::getProfile($regionId, $AccessKeyId, $AccessKeySecret);
        $this->client = new DefaultAcsClient($profile);
        $this->vodService = $vodService;
    }

    /**
     *[getPlayAuth 获取阿里云视频凭证]
     *
     */
    public function getPlayAuth(Request $request){
        try {
            $videoId = $request->get('VideoId');
            $playAuth = $this->vodService->getPlayAuth($videoId);
            $result = array(
                'code'=>200,
                'msg'=>'success',
                'resp'=>$playAuth,
            );
        } catch (\Exception $e) {
            $result = array(
                'code'=> $e->getCode(),
                'msg'=>$e->getMessage(),
            );
        }
        return response()->json($result);
    }

    /**
     *[PushObjectCache 获取阿里云视频播放地址]
     *
     */
    public function getPlayInfo(Request $request){
        try {
            $videoId = $request->get('VideoId');
            $playInfo = $this->vodService->getPlayInfo($videoId);
            $result = array(
                'code'=>200,
                'msg'=>'success',
                'resp'=>$playInfo,
            );
        } catch (\Exception $e) {
            $result = array(
                'code'=> $e->getCode(),
                'msg'=>$e->getMessage(),
            );
        }
        return response()->json($result);
    }

    /**
     *[PushObjectCache 阿里云视频预热缓存]
     *
     */
    public function PushObjectCache(Request $request){
        try {
            $objectPath = $request->get('ObjectPath');
            $videoCache = $this->vodService->pushObjectCache($objectPath);
            $result = array(
                'code'=>200,
                'msg'=>'success',
                'resp'=>$videoCache,
            );
        } catch (\Exception $e) {
            $result = array(
                'code'=> $e->getCode(),
                'msg'=>$e->getMessage(),
            );
        }
        return response()->json($result);
    }


    /**
     *[RefreshObjectCache  阿里云视频刷新缓存]
     *
     */
    public function RefreshObjectCache(Request $request){
        try {
            $objectPath = $request->get('ObjectPath');
            $videoCache = $this->vodService->refreshObjectCache($objectPath);
            $result = array(
                'code'=>200,
                'msg'=>'success',
                'resp'=>$videoCache,
            );
        } catch (\Exception $e) {
            $result = array(
                'code'=> $e->getCode(),
                'msg'=>$e->getMessage(),
            );
        }
        return response()->json($result);
    }


    /**
     *[CreateUploadVideo  获取阿里云视频上传地址and 凭证]
     *
     */
    public function CreateUploadVideo(Request $request){
        try {
            $Title = $request->title;
            $FileName = $request->filename;
            $FileSize = $request->filesize;
            $cateId = isset($request->cateId)? $request->cateId : '';
            $Description = isset($request->Description)?$request->Description : '';
            //上传封面
            if ($request->hasFile('CoverURL')) {
                $CoverURL = $request->CoverURL->store('upload');
                $coverPath =  asset('storage/'.$CoverURL);
            }
            $options = array(
                'title' => $Title,
                'filename' => $FileName,
                'fileSize' => $FileSize,
                'cateId' => $cateId,
                'description' => $Description,
                'CoverURL'=>isset($coverPath)? $coverPath :'',
            );
            $uploadRes = $this->vodService->getUploadAuth($options);
            $result = array(
                'code'=>200,
                'msg'=>'success',
                'resp'=>$uploadRes,
            );
        } catch (\Exception $e) {
            $result = array(
                'code'=> $e->getCode(),
                'msg'=>$e->getMessage(),
            );
        }
        return response()->json($result);
    }


    /**
     *[RefreshUploadVideo  刷新阿里云视频上传地址and 凭证]
     *
     */
    public function RefreshUploadVideo(Request $request){
        try {
            $VideoId = $request->VideoId;
            $client = $this->client;
            $RefreshUploadVideoRequest = new vod\RefreshUploadVideoRequest();
            $RefreshUploadVideoRequest->setVideoId($VideoId);
            $refreshRes = $client->getAcsResponse($RefreshUploadVideoRequest);
            $result = array(
                'code'=>200,
                'msg'=>'success',
                'resp'=>$refreshRes,
            );
        } catch (\Exception $e) {
            $result = array(
                'code'=> $e->getCode(),
                'msg'=>$e->getMessage(),
            );
        }
        return response()->json($result);
    }

    public function ApiUploadVideo(Request $request){
        try {
            // 初始化VOD客户端并获取上传地址和凭证
            $localFile = file_get_contents('php://input');//文件数据流
            $Title = $request->title;
//            $fp = fopen(base_path('听妈妈的话.mp4'),'rb');
//            $localFile = fread($fp,filesize(base_path('听妈妈的话.mp4')));
//            $Title = "听妈妈的话";
            $FileName = isset($request->filename)? $request->filename :'test.mp4';
            $cateId = isset($request->cateId)? $request->cateId : '818583554';
            $Description = isset($request->Description)?$request->Description : '';

            $client = $this->client;
            $CreateUploadVideoRequest = new vod\CreateUploadVideoRequest();
            $CreateUploadVideoRequest->setTitle($Title);
            $CreateUploadVideoRequest->setFileName($FileName);
            //这里设置视频分类ID  cateID 在阿里云后台获取
            !empty($cateId)  && $CreateUploadVideoRequest->setCateId($cateId);
            !empty($Description)  && $CreateUploadVideoRequest->setDescription($Description);
            $createRes = $client->getAcsResponse($CreateUploadVideoRequest);

            // 执行成功会返回VideoId、UploadAddress和UploadAuth
            $videoId = $createRes->VideoId;
            $uploadAddress = json_decode(base64_decode($createRes->UploadAddress), true);
            $uploadAuth = json_decode(base64_decode($createRes->UploadAuth), true);

            // 使用UploadAuth和UploadAddress初始化OSS客户端
            $ossClient = $this->init_oss_client($uploadAuth, $uploadAddress);

            // 上传文件，注意是同步上传会阻塞等待，耗时与文件大小和网络上行带宽有关
            //$result = $this->>upload_local_file($ossClient, $uploadAddress, $localFile);
            $res = $this->multipart_upload_file($ossClient, $uploadAddress, $localFile);
            $result = array(
                'code'=>200,
                'msg'=>'success',
                'resp'=>['videoId'=>$videoId],
            );
        } catch (\Exception $e) {
            $result = array(
                'code'=> $e->getCode(),
                'msg'=>$e->getMessage(),
            );
        }
        return response()->json($result);

    }


    // 使用上传凭证和地址信息初始化OSS客户端（注意需要先Base64解码并Json Decode再传入）
    private function init_oss_client($uploadAuth, $uploadAddress) {
        $ossClient = new OssClient($uploadAuth['AccessKeyId'], $uploadAuth['AccessKeySecret'], $uploadAddress['Endpoint'],
            false, $uploadAuth['SecurityToken']);
        $ossClient->setTimeout(86400*7);    // 设置请求超时时间，单位秒，默认是5184000秒, 建议不要设置太小，如果上传文件很大，消耗的时间会比较长
        $ossClient->setConnectTimeout(10);  // 设置连接超时时间，单位秒，默认是10秒
        return $ossClient;
    }

    public function multipart_upload_file($ossClient, $uploadAddress, $localFile) {
        try{
            return $ossClient->multiuploadFile($uploadAddress['Bucket'], $uploadAddress['FileName'], $localFile);
        } catch(OssException $e) {
            var_dump($e);die;
        }
    }

    // 使用简单方式上传本地文件：适用于小文件上传；最大支持5GB的单个文件
    public function upload_local_file($ossClient, $uploadAddress, $localFile) {
        try{
            return $ossClient->uploadFile($uploadAddress['Bucket'], $uploadAddress['FileName'], $localFile);
        } catch(OssException $e) {
            printf(__FUNCTION__ . ": FAILED\n");
            printf($e->getMessage() . "\n");
        }
    }























}
