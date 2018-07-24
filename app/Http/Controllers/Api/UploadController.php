<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/19
 * Time: 16:13
 */

namespace App\Http\Controllers\Api;

use App\Exceptions\APIException;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\InitMultiRequest;
use App\Http\Requests\API\UploadAuthRequest;
use App\Http\Requests\API\UploadCompleteRequest;
use App\Http\Requests\API\UploadPartRequest;
use App\Http\Services\VideoService;
use App\Http\Services\VodService;
use App\Models\Admin\Video;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use OSS\Core\OssException;
use OSS\OssClient;

class UploadController extends Controller
{
    public function auth(VodService $service, UploadAuthRequest $request)
    {
        $options = [
            'title' => $request->title,
            'filename' => $request->filename,
            'fileSize' => $request->fileSize,
        ];

        $data = (array)$service->getUploadAuth($options);

        $token = $this->getAuthKey($data['UploadAuth'], $data['UploadAddress']);

        Cache::put($token, array_merge($data, $options), 60);

        $videoId = $data['VideoId'];
        $requestId = $data['RequestId'];

        return response()->json([
            'code' => 0,
            'data' => compact('token', 'videoId', 'requestId')
        ]);
    }

    public function simple()
    {

    }

    public function initMulti(InitMultiRequest $request)
    {
        try {
            $data = Cache::get($request->token);

            if (empty($data)) {
                throw new APIException('token错误或过期');
            }

            $uploadAddress = $this->parseUploadAddress($data['UploadAddress']);
            $uploadAuth = $this->parseUploadAuth($data['UploadAuth']);

            $ossClient = $this->getOssClient($uploadAuth, $uploadAddress);
            $uploadId = $ossClient->initiateMultipartUpload($uploadAddress['Bucket'], $uploadAddress['FileName']);

            return response()->json(['code' => 0, 'data' => $uploadId]);
        } catch (OssException $e) {

            return response()->json(['code' => 1, 'msg' => $e->getMessage()]);
        } catch (APIException $e) {

            return response()->json(['code' => 1, 'msg' => $e->getMessage()]);
        }
    }

    private function parseUploadAddress($uploadAddress)
    {
        return json_decode(base64_decode($uploadAddress), true);
    }

    private function parseUploadAuth($uploadAuth)
    {
        return json_decode(base64_decode($uploadAuth), true);
    }

    /**
     * @param $uploadAuth
     * @param $uploadAddress
     * @return OssClient
     * @throws \OSS\Core\OssException
     */
    private function getOssClient($uploadAuth, $uploadAddress)
    {
        $ossClient = new OssClient(
            $uploadAuth['AccessKeyId'],
            $uploadAuth['AccessKeySecret'],
            $uploadAddress['Endpoint'],
            false,
            $uploadAuth['SecurityToken']
        );
        $ossClient->setTimeout(86400 * 7);    // 设置请求超时时间，单位秒，默认是5184000秒, 建议不要设置太小，如果上传文件很大，消耗的时间会比较长
        $ossClient->setConnectTimeout(10);  // 设置连接超时时间，单位秒，默认是10秒
        return $ossClient;
    }

    /**
     * 分片上传
     * @param UploadPartRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function part(UploadPartRequest $request)
    {
        try {
            $data = Cache::get($request->token);

            if (empty($data)) {
                throw new APIException('token错误或过期');
            }

            $uploadId = $request->uploadId;
            $partNumber = $request->partNumber;
            $md5 = $request->checkMd5 ? $request->checkMd5 : md5(time() . rand(10000, 99999));
            $uploadAuth = $this->parseUploadAuth($data['UploadAuth']);
            $uploadAddress = $this->parseUploadAddress($data['UploadAddress']);

            $content = file_get_contents('php://input', 'rb');

            $options = [
                OssClient::OSS_PART_NUM => $partNumber,
                OssClient::OSS_LENGTH => strlen($content),
                OssClient::OSS_CHECK_MD5 => $md5,
                OssClient::OSS_UPLOAD_ID => $uploadId
            ];

            $ossClient = $this->getOssClient($uploadAuth, $uploadAddress);

            $result = $ossClient->putObject($uploadAddress['Bucket'], $uploadAddress['FileName'], $content, $options);

            if (!isset($result['etag'])) {
                throw new APIException("返回数据错误");
            }

            $part = $result['etag'];

            Log::info($part);

            Cache::put($this->getUploadPartKey($uploadId, $partNumber), ['ETag' => $part, 'PartNumber' => $partNumber], 120);

            return response()->json(['code' => 0, 'data' => $part]);
        } catch (OssException $e) {
            Log::error($e);
            return response()->json(['code' => 1, 'msg' => $e->getMessage()]);
        } catch (APIException $e) {
            Log::error($e);
            return response()->json(['code' => 1, 'msg' => $e->getMessage()]);
        }
    }

    /**
     * 分片上传完成调用接口, 传入totalPartNumber
     * @param UploadCompleteRequest $request
     * @param VideoService $videoService
     * @return \Illuminate\Http\JsonResponse
     */
    public function complete(UploadCompleteRequest $request, VideoService $videoService)
    {
        try {

            $data = Cache::get($request->token);

            if (empty($data)) {
                throw new APIException('token错误或过期');
            }

            $uploadAuth = $this->parseUploadAuth($data['UploadAuth']);
            $uploadAddress = $this->parseUploadAddress($data['UploadAddress']);
            $uploadId = $request->uploadId;

            $parts = [];

            for ($partNumber = 1; $partNumber <= $request->totalPartNumber; $partNumber++) {
                $part = Cache::get($this->getUploadPartKey($uploadId, $partNumber));

                if (empty($part)) {
                    throw new APIException("partNumber: {$partNumber} 错误");
                }

                $parts[] = $part;
            }

            $ossClient = $this->getOssClient($uploadAuth, $uploadAddress);
            $completeData = $ossClient->completeMultipartUpload($uploadAddress['Bucket'], $uploadAddress['FileName'], $uploadId, $parts);

            Log::info($completeData);

            // 保存到库
            $videoService->storeVideo([
                'title' => $data['title'],
                'aliyun_id' => $data['VideoId'],
                'level' => Video::LEVEL_ALWAYS,
                'current_type' => Video::TYPE_ALIYUN
            ]);

            return response()->json(['code' => 0, 'data' => $data['VideoId']]);
        } catch (OssException $e) {
            Log::error($e);
            return response()->json(['code' => 1, 'msg' => $e->getMessage()]);
        } catch (APIException $e) {
            Log::error($e);
            return response()->json(['code' => 1, 'msg' => $e->getMessage()]);
        }
    }

    private function getAuthKey($uploadAuth, $uploadAddress)
    {
        return md5($uploadAuth . $uploadAddress);
    }

    private function getUploadPartKey($uploadId, $partNum)
    {
        return "{$uploadId}:{$partNum}";
    }
}