<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/19
 * Time: 16:41
 */

namespace App\Http\Services;

use DefaultAcsClient;
use DefaultProfile;
use OSS\OssClient;
use vod\Request\V20170321 as vod;

class VodService
{
    private $client;

    public function __construct()
    {
        $AccessKeyId = \Config::get('constants.VOD_ACCESS_ID');
        $AccessKeySecret = \Config::get('constants.VOD_ACCESS_SCERET');
        $regionId = 'cn-shanghai';
        $profile = DefaultProfile::getProfile($regionId, $AccessKeyId, $AccessKeySecret);
        $this->client = new DefaultAcsClient($profile);
    }

    public function getUploadAuth($options)
    {
        $title = $options['title'];
        $filename = $options['filename'];
        $fileSize = $options['fileSize'];
        $cateId = isset($options['cateId'])? $options['cateId'] : '';
        $description = isset($options['description'])?$options['description'] : '';
        $coverURL = isset($options['CoverURL'])? $options['CoverURL'] : '';
        $CreateUploadVideoRequest = new vod\CreateUploadVideoRequest();
        $CreateUploadVideoRequest->setTitle($title);
        $CreateUploadVideoRequest->setFileName($filename);
        $CreateUploadVideoRequest->setFileSize($fileSize);
        //这里设置视频分类ID  cateID 在阿里云后台获取
        !empty($cateId)  && $CreateUploadVideoRequest->setCateId($cateId);
        !empty($description)  && $CreateUploadVideoRequest->setDescription($description);
        !empty($coverURL) && $CreateUploadVideoRequest->setCoverURL($coverURL);
        $response = $this->client->getAcsResponse($CreateUploadVideoRequest);

        return $response;
    }

    public function initiateMultipartUpload(OssClient $ossClient, $bucket, $object) {
        return $ossClient->initiateMultipartUpload($bucket, $object);
    }

    /**
     * @param $options
     * @return mixed|\SimpleXMLElement
     * 获取播放凭证
     */
    public function getPlayAuth($videoId){
        $playAuthRequest = new vod\GetVideoPlayAuthRequest();
        $playAuthRequest->setVideoId($videoId);
        $playAuthRequest->setAuthInfoTimeout(3600);
        $playAuthRequest->setAcceptFormat('JSON');
        $playAuth = $this->client->getAcsResponse($playAuthRequest);
        return $playAuth;
    }

    /**
     * @param $options
     * @return mixed|\SimpleXMLElement
     */
    public function getPlayInfo($videoId){
        $playInfoRequest = new vod\GetPlayInfoRequest();
        $playInfoRequest->setVideoId($videoId);
        $playInfoRequest->setAuthTimeout(3600);
        $playInfo = $this->client->getAcsResponse($playInfoRequest);
        return $playInfo;
    }

    /**
     * @param $options
     * @return mixed|\SimpleXMLElement
     */
    public function pushObjectCache($objectPath){
        $videoCacheRequest = new vod\PushObjectCacheRequest();
        $videoCacheRequest->setObjectPath($objectPath);
        $videoCache = $this->client->getAcsResponse($videoCacheRequest);
        return $videoCache;
    }

    /**
     * @param $option
     * @return mixed|\SimpleXMLElement
     */
    public function refreshObjectCache($objectPath){
        $refreshCacheRequest = new vod\RefreshObjectCachesRequest();
        $refreshCacheRequest->setObjectPath($objectPath);
        $videoCache = $this->client->getAcsResponse($refreshCacheRequest);
        return $videoCache;
    }

    /**
     * @param $option
     * @return mixed|\SimpleXMLElement
     */
    public function refreshUploadVideo($videoId){
        $RefreshUploadVideoRequest = new vod\RefreshUploadVideoRequest();
        $RefreshUploadVideoRequest->setVideoId($videoId);
        $refreshRes = $this->client->getAcsResponse($RefreshUploadVideoRequest);
        return $refreshRes;
    }









}