<?php

namespace App\Http\Api\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Dingo\Api\Routing\Helpers;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class RegisterController extends Controller
{
    use RegistersUsers;
    use Helpers;

    public function register(Request $request)
    {
        $validator = $this->validator($request->all());
        if ($validator->fails()) {
            throw new StoreResourceFailedException("Validation Error", $validator->errors());
        }
        $user = $this->create($request->all());

        if ($user->save()) {

            $token = JWTAuth::fromUser($user);

            return $this->response->array([
                "token" => $token,
                "message" => "注册成功",
                "status_code" => 201,
            ]);
        } else {
            return $this->response->error("User Not Found...", 404);
        }
    }

    public function validator($data)
    {
        return Validator::make($data, [
            'name' => 'required|unique:users|max:10',
            'email' => 'required|unique:users|email',
            'password' => 'required|min:6']);
    }

    public function sendFailResponse($message)
    {
        return $this->response->error($message, 400);
    }

    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }
}

