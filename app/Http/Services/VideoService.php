<?php
/**
 *  视频服务
 */

namespace App\Http\Services;

use App\Exceptions\APIException;
use App\Exceptions\DhvodException;
use App\Http\Requests\Request;
use App\Models\Admin\Video;


class VideoService
{
    protected $Repo;
    protected $validator;


    /**
     *  [transmit 视频请求转发]
     */
    public function transmit($key)
    {
            $video = Video::where('aliyun_id', $key)
                ->orWhere('youku_id', $key)
                ->get()->toArray();
            if (empty($video)) {
                return response()->json(['msg' => 'invalid key', 'code' => 400]);
            }
            $video = $video[0];
            if ($video['current_type'] == 1) {
                $key_arr = ['aliyun_id' => $video['aliyun_id'], 'youku_id' => $video['youku_id']];
            } else {
                $key_arr = ['aliyun_id' => $video['aliyun_id'], 'youku_id' => ''];
            }
            $result = array(
                'resp' => array(
                    'type' => $video['current_type'],
                    'key' => $key_arr
                ),
                'code' => 200,
                'msg' => 'success'
            );
            return response()->json($result);

    }

    /**
     * [transform 视频切换脚本]
     *
     */


    public function transform()
    {
        try {
            $videos = Video::whereIn('level', [2, 3, 4])->get()->toArray();
            if (!empty($videos)) {
                foreach ($videos as $video) {
                    if ($video['level'] == 2) {
                        if ($video['updated_at'] == '0000-00-00 00:00:00') {
                            (time() - strtotime($video['created_at']) > 30 * 86400) && Video::where('id', $video['id'])->update(['current_type' => 2]);
                        } else {
                            (time() - strtotime($video['updated_at']) > 30 * 86400) && Video::where('id', $video['id'])->update(['current_type' => 2]);
                        }
                    } elseif ($video['level'] == 3) {
                        if ($video['updated_at'] == '0000-00-00 00:00:00') {
                            (time() - strtotime($video['created_at']) > 3 * 30 * 86400) && Video::where('id', $video['id'])->update(['current_type' => 2]);
                        } else {
                            (time() - strtotime($video['updated_at']) > 3 * 30 * 86400) && Video::where('id', $video['id'])->update(['current_type' => 2]);
                        }
                    } elseif ($video['level'] == 4) {
                        if ($video['updated_at'] == '0000-00-00 00:00:00') {
                            (time() - strtotime($video['created_at']) > 6 * 30 * 86400) && Video::where('id', $video['id'])->update(['current_type' => 2]);
                        } else {
                            (time() - strtotime($video['updated_at']) > 6 * 30 * 86400) && Video::where('id', $video['id'])->update(['current_type' => 2]);
                        }
                    }
                }
            }
            return response()->json(['msg' => 'success', 'code' => 200]);
        } catch (\Exception $e) {
            return response()->json(['msg' => $e->getMessage(), 'code' => $e->getCode()]);
        }
    }


    /**
     * 保存Video数据入库
     * @param $params
     * @throws APIException
     */
    public function storeVideo($params)
    {
        $Video = new Video();
        $Video->title = $params['title'];
        $Video->aliyun_id = $params['aliyun_id'];
        $Video->level = $params['level'];
        $Video->current_type = $params['current_type'];
        $Video->youku_id = isset($params['youku_id']) ? $params['youku_id'] : '';
        if ($Video->save() == false) {
            throw new APIException("保存数据失败");
        }
    }
}