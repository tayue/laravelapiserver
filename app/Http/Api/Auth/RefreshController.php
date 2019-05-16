<?php

namespace App\Http\Api\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Facades\JWTAuth;
use Dingo\Api\Routing\Helpers;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class RefreshController extends Controller
{
    use AuthenticatesUsers;
    use Helpers;
    public function refresh(Request $request)
    {
        $old_token=JWTAuth::gettoken();    //获取过期token
        $new_token=JWTAuth::refresh($old_token);    //刷新token并返回
        JWTAuth::invalidate($old_token);    //销毁过期token
        return $this->response->array([
            'token'=>$new_token,
            'status_code'=>201
        ]);
    }
}

