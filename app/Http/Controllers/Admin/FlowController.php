<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FlowController extends Controller
{

    public function index(Request $request)
    {
        return view('data.flow');
    }

}
