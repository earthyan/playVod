<?php
/**
 * Created by PhpStorm.
 * User: Hainan
 * Date: 2018/4/19
 * Time: 10:20
 */

namespace App\Http\Controllers\Admin;
use App\Http\Services\AmsService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AmsController extends Controller
{

    private $service;

    public function __construct(AmsService $AmsService)
    {
        $this->service = $AmsService;
    }

    public function index(){
        $data = $this->service->getUser();
        if ($data) {
            return redirect('/admin/log-viewer');
        }else{
            return redirect('/admin/login');
        }
    }

    public function login(Request $request){
        $callback_url = str_replace('login','callback',$request->url());
        $url = $this->service->login($callback_url);
        return redirect($url);
    }

    public function callback(Request $request){
        $key = $request->key;
        $data = $this->service->callback($key);
        if ($data['code'] == 0) {
            Auth::guard()->loginUsingId($data['user']->id);
            return redirect('/admin/log-viewer');
        }
    }

    public function logout(Request $request){
        $callback_url = str_replace('logout','login',$request->url());
        $url = $this->service->logout($callback_url);
        $request->session()->flush();
        return redirect($url);
    }
}