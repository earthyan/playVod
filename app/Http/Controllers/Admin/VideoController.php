<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests\VideoCreateRequest;
use App\Http\Requests\VideoUpdateRequest;
use App\Http\Controllers\Controller;
use App\Models\Admin\Video;
use DefaultAcsClient;
use DefaultProfile;
use vod\Request\V20170321 as vod;

class VideoController extends Controller
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

    protected $fields = [
        'title' => '',
        'youku_id' => '',
        'aliyun_id' => '',
        'level' => 1,
        'current_type' => 1,
    ];


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = array();
            $data['draw'] = $request->get('draw');
            $start = $request->get('start');
            $length = $request->get('length');
            $order = $request->get('order');
            $columns = $request->get('columns');
            $search = $request->get('search');
            $data['recordsTotal'] = Video::count();
            if (strlen($search['value']) > 0) {
                $data['recordsFiltered'] = Video::where(function ($query) use ($search) {
                    $query->where('title', 'LIKE', '%' . $search['value'] . '%')
                        ->orWhere('youku_id', 'like', '%' . $search['value'] . '%')
                        ->orWhere('aliyun_id', 'like', '%' . $search['value'] . '%');
                })->count();
                $data['data'] = Video::where(function ($query) use ($search) {
                    $query->where('title', 'LIKE', '%' . $search['value'] . '%')
                        ->orWhere('youku_id', 'like', '%' . $search['value'] . '%')
                        ->orWhere('aliyun_id', 'like', '%' . $search['value'] . '%');
                })
                    ->skip($start)->take($length)
                    ->orderBy($columns[$order[0]['column']]['data'], $order[0]['dir'])
                    ->get();
            } else {
                $data['recordsFiltered'] = Video::count();
                $data['data'] = Video::
                skip($start)->take($length)
                    ->orderBy($columns[$order[0]['column']]['data'], $order[0]['dir'])
                    ->get();
            }
            return response()->json($data);
        }
        return view('admin.video.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = [];
        foreach ($this->fields as $field => $default) {
            $data[$field] = old($field, $default);
        }
        return view('admin.video.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param VideoCreateRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(VideoCreateRequest $request)
    {
        $Video = new Video();
        foreach (array_keys($this->fields) as $field) {
            $Video->$field = $request->get($field);
        }
        $youku_id = $request->youku_id;
        if(strlen($youku_id)>15){
            $Video->youku_id = $this->matchYkuUrl($youku_id);
        }
        $Video->save();
        event(new \App\Events\userActionEvent('\App\Models\Admin\Video',$Video->id,1,"用户".auth()->user()->username."{".auth()->user()->id."}添加视频".$Video->title."{".$Video->id."}"));
        return redirect('/admin/video')->withSuccess('添加成功！');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $Video = Video::find((int)$id);
        if (!$Video) return redirect('/admin/Video')->withErrors("找不到该视频!");
        foreach (array_keys($this->fields) as $field) {
            $data[$field] = old($field, $Video->$field);
        }
        $data['id'] = (int)$id;
        return view('admin.video.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param PermissionUpdateRequest|Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(VideoUpdateRequest $request, $id)
    {
        $Video = Video::find((int)$id);
        foreach (array_keys($this->fields) as $field) {
            $Video->$field = $request->get($field);
        }
        $youku_id = $request->youku_id;
        if(strlen($youku_id)>15){
            $Video->youku_id = $this->matchYkuUrl($youku_id);
        }
        $Video->save();
        event(new \App\Events\userActionEvent('\App\Models\Admin\Video',$Video->id,3,"用户".auth()->user()->name."{".auth()->user()->id."}编辑视频".$Video->name."{".$Video->id."}"));
        return redirect('/admin/video')->withSuccess('修改成功！');
    }

    /**
     * 正则表达式匹配优酷网址
     *获取youku_id
     */
    public function matchYkuUrl($url){
        $pos1 = strpos($url,'id_');
        $pos2 = strpos($url,'.html');
        if($pos1 != false && $pos2!=false){
            $str = substr($url,($pos1+3),-($pos2+1));
        }else{
            $str = $url;
        }
        return $str;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $Video = Video::find((int)$id);
        if ($Video) {
            $Video->delete();
        } else {
            return redirect()->back()
                ->withErrors("删除失败");
        }
        event(new \App\Events\userActionEvent('\App\Models\Admin\Video',$Video->id,2,"用户".auth()->user()->name."{".auth()->user()->id."}删除视频".$Video->name."{".$Video->id."}"));
        return redirect()->back()
            ->withSuccess("删除成功");
    }


    /**
     * [预热视频]
     *
     */
    public function push(Request $request)
    {
        try{
            $VideoId = $request->input('aliyun_id');
            $client = $this->client;
            $playInfoRequest = new vod\GetPlayInfoRequest();
            $playInfoRequest->setVideoId($VideoId);
            $playInfoRequest->setAuthTimeout(3600);
            $playInfo = $client->getAcsResponse($playInfoRequest);
            $playInfo = $playInfo->PlayInfoList->PlayInfo;
            $objectPath = $playInfo[0]->PlayURL;

            $url = url('api/PushObjectCache')."?ObjectPath=".$objectPath;
            $res = $this->curl_get($url);
            $data = json_decode($res,true);
            if($data['code']!=200){
                $request->session()->flash('errors', '视频预热失败');
            }else{
                $request->session()->flash('success', '视频预热成功');
            }
            return $res;
        }catch (\Exception $e){
            $request->session()->flash('errors', '视频预热失败');
            return json_encode(['code'=>400,'msg'=>'The video does not exist']);
        }


    }


    /**
     * [刷新视频]
     *
     */
    public function refresh(Request $request)
    {
        try{
            $VideoId = $request->input('aliyun_id');
            $client = $this->client;
            $playInfoRequest = new vod\GetPlayInfoRequest();
            $playInfoRequest->setVideoId($VideoId);
            $playInfoRequest->setAuthTimeout(3600);
            $playInfo = $client->getAcsResponse($playInfoRequest);
            $playInfo = $playInfo->PlayInfoList->PlayInfo;
            $objectPath = $playInfo[0]->PlayURL;

            $url = url('api/RefreshObjectCache')."?ObjectPath=".$objectPath;
            $res = $this->curl_get($url);
            $data = json_decode($res,true);
            if($data['code']!=200){
                $request->session()->flash('errors', '视频刷新缓存失败');
            }else{
                $request->session()->flash('success', '视频刷新缓存成功');
            }
            return $res;
        }catch (\Exception $e){
            $request->session()->flash('errors', '视频刷新失败');
            return json_encode(['code'=>400,'msg'=>'The video does not exist']);
        }
    }

    private function curl_get($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }

}
