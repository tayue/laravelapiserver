<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\Client;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function oauth()
    {

        $query = http_build_query([
            'client_id' => 2,
            'redirect_uri' => 'http://localhost/auth/callback',
            'response_type' => 'code',
            'scope' => '*',
        ]);

        return redirect('http://api.demo.test//oauth/authorize?'.$query);
    }

    public function callback(Request $request)
    {
        $code = $request->get('code');
        if (!$code) {
            dd('授权失败');
        }
        $http = new Client();
        $response = $http->post('http://api.demo.test/oauth/token', [
            'form_params' => [
                'grant_type' => 'authorization_code',
                'client_id' => 2,  // your client id
                'client_secret' => '4JbgXiG7VlViyn9gMWUHyti1fFYAvOo16K64fxOX',   // your client secret
                'redirect_uri' => 'http://localhost/auth/callback',
                'code' => $code,
            ],
        ]);

        return response($response->getBody());
    }


}
