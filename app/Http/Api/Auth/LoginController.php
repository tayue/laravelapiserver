<?php

namespace App\Http\Api\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\User;
use Dingo\Api\Routing\Helpers;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    use AuthenticatesUsers;
    use Helpers;
    public function login(Request $request)
    {
        $user=User::where('name',$request->email)->orwhere('email',$request->email)->firstOrFail();
        if($user && Hash::check($request->password,$user->password)){
            $token=JWTAuth::fromuser($user);    //获取token
            $this->clearLoginAttempts($request);  //清除登录次数
            return $this->response->array([
                'token'=>$token,
                'message'=>"Login Success",
                'status_code'=>200
            ]);
        }
        else{
            throw new UnauthorizedHttpException("Login Failed");
        }
    }
    public function logout(){
        JWTAuth::invalidate(JWTAuth::getToken());    //token加入黑名单(注销)
        $this->guard()->logout();
    }

}

