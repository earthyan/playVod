<?php
namespace App\Http\Controllers\Api;

use App\Http\Services\VideoService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class VideoController extends Controller
{
    protected $service;

    public function __construct(VideoService $service)
    {
        $this->service = $service;
    }

    /***
     * transmit 视频请求转发
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function transmit($key){
        return $this->service->transmit($key);
    }

    /****
     * [transmit 视频源时间脚本]
     * @return \Illuminate\Http\JsonResponse
     */
    public function transform(){
        return $this->service->transform();
    }


    /***
     * 存储视频
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeVideo(Request $request){
        if ($request->has('youku_id')) {
            $youku_id = $request->input('youku_id');
            if (strlen($youku_id) > 15) {
                $pos1 = strpos($youku_id, 'id_');
                $pos2 = strpos($youku_id, '.html');
                if ($pos1 != false && $pos2 != false) {
                    $youku_id = substr($youku_id, ($pos1 + 3), -($pos2 + 1));
                }
            }
        } else {
           $youku_id = '';
        }
        $params = array(
            'youku_id'=>$youku_id,
            'title'=> $request->input('title'),
            'aliyun_id'=>$request->input('aliyun_id'),
            'level'=>$request->input('level'),
            'current_type'=>$request->input('current_type'),
        );
        $data = $this->service->storeVideo($params);
        return response()->json($data);
    }

}
