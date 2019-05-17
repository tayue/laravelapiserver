<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Article;
use App\Models\Comment;
use Laravel\Passport\Passport;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});


//Route::group([
//
//    'prefix' => 'auth'
//
//], function ($router) {
//
//    Route::post('login', 'AuthController@login')->name('login');
//    Route::post('logout', 'AuthController@logout');
//    Route::get('refresh', 'AuthController@refresh');
//    Route::post('me', 'AuthController@me');
//    Route::post('register', 'AuthController@register');
//    Route::get('user', 'AuthController@user');
//
//    Route::get('articles', 'ArticleController@index');
//});
Route::get('articles', 'ArticleController@index');

Route::get('users', 'ArticleController@user');

//Route::group(['middleware' => 'auth:api'], function() { //验证中间件auth:api
//    Route::get('articles', 'ArticleController@index');
//    Route::get('articles/{article}', 'ArticleController@show');
//    Route::post('articles', 'ArticleController@store');
//    Route::put('articles/{article}', 'ArticleController@update');
//    Route::delete('articles/{article}', 'ArticleController@delete');
//});


//Route::get('register/index', 'Auth\RegisterController@index');
//
//Route::post('register', 'Auth\RegisterController@register')->name('register');
//
//Route::post('login', 'Auth\LoginController@login')->name('login');
//
//Route::post('logout', 'Auth\LoginController@logout');


//Route::get('articles', 'ArticleController@index');
//Route::get('articles/{id}', 'ArticleController@show');
//Route::post('articles', 'ArticleController@store');
//Route::put('articles/{id}', 'ArticleController@update');
//Route::delete('articles/{id}', 'ArticleController@delete');


//dinggo api

$api = app(\Dingo\Api\Routing\Router::class);

$api->version('v1', function ($api) {
    $api->post('login', 'App\Http\Api\Auth\LoginController@login');
    $api->post('register', 'App\Http\Api\Auth\RegisterController@register');
    $api->get('task/{id}', 'App\Http\Api\Auth\TaskController@show');

    $api->get('tasks', 'App\Http\Api\Auth\TaskController@index');
    $api->post('formvalidate', 'App\Http\Api\Auth\TaskController@store');
    $api->get('page', 'App\Http\Api\Auth\TaskController@page');
    //Dingo 格式转化器 Fractal使用

    //League\Fractal\Resource\Item：单个资源
    $api->get('articleFractalItem/{id}', function ($id) {

       // @$article=Article::where('id', '=',$id)->get();
        $article=Article::findOrFail($id);
      //  var_dump($article);


         $resource = new \League\Fractal\Resource\Item($article, new \App\Transformers\ArticleTransformer());

//        $resource = new \League\Fractal\Resource\Item($article, function (\App\Models\Article $task) {
//            return [
//                'id' => $article->id,
//                'title' => $article->title,
//                'body' => $article->body
//            ];
//        });
        $fractal = new \League\Fractal\Manager();

        return $fractal->parseIncludes('comment')->createData($resource)->toJson();

        //return $fractal->createData($resource)->toJson();
    });

    //League\Fractal\Resource\Collection：资源集合
    $api->get('commentFractalCollections/{userId}', function ($userId) {
        $comments=Comment::where('user_id', '=',$userId)->get();
        $resource = new \League\Fractal\Resource\Collection($comments, function (\App\Models\Comment $comments) {
            return [
                'id' => $comments->id,
                'user_id' => $comments->user_id,
                'content' => $comments->content,
                'status' => $comments->status
            ];
        });
        $fractal = new \League\Fractal\Manager();
        return $fractal->createData($resource)->toJson();
    });


    //序列化器使用(ArraySerializer)
    $api->get('/fractal/arraySerializer/{id}', function ($id) {
        @$article=Article::where('id', '=',$id)->get()->pop();
        $resource = new \League\Fractal\Resource\Item($article, function (\App\Models\Article $task) {
            return [
                'id' => $task->id,
                'text' => $task->body

            ];
        });
        $fractal = new \League\Fractal\Manager();
        $fractal->setSerializer(new \League\Fractal\Serializer\ArraySerializer());
        return $fractal->createData($resource)->toJson();
    });


    //序列化器使用(DataArraySerializer)需要指出的是，DataArraySerializer 是 Fractal 默认的数据输出格式
    $api->get('/fractal/dataArraySerializer/{id}', function ($id) {
        @$article=Article::where('id', '=',$id)->get()->pop();
        $resource = new \League\Fractal\Resource\Item($article, function (\App\Models\Article $task) {
            return [
                'id' => $task->id,
                'text' => $task->body

            ];
        });
        $fractal = new \League\Fractal\Manager();
        $fractal->setSerializer(new \League\Fractal\Serializer\DataArraySerializer());
        return $fractal->createData($resource)->toJson();
    });

    //序列化器使用(JsonApiSerializer)
    $api->get('/fractal/jsonApiSerializer/{id}', function ($id) {
        @$article=Article::where('id', '=',$id)->get()->pop();
        $resource = new \League\Fractal\Resource\Item($article, function (\App\Models\Article $task) {
            return [
                'id' => $task->id,
                'text' => $task->body

            ];
        });
        $fractal = new \League\Fractal\Manager();
        $fractal->setSerializer(new \League\Fractal\Serializer\JsonApiSerializer());
        return $fractal->createData($resource)->toJson();
    });

    //使用分页器 包括项目总数、上一页/下一页链接等，但相应的代价是可能会带来额外的性能开销
    $api->get('fractal/paginator', function () {
        $paginator = \App\Models\Article::paginate();
        $articles = $paginator->getCollection();

        $resource = new \League\Fractal\Resource\Collection($articles, new \App\Transformers\ArticleTransformer());
        $resource->setPaginator(new \League\Fractal\Pagination\IlluminatePaginatorAdapter($paginator));

        $fractal = new \League\Fractal\Manager();
        return $fractal->parseIncludes('comment')->createData($resource)->toJson();
    });

    //使用游标分页
    $api->get('fractal/cursor', function (Request $request) {
        $current = $request->input('current',10);
        $previous = $request->input('previous',0);
        $limit = $request->input('limit', 10);

        if ($current) {
            $tasks = \App\Models\Article::where('id', '>', $current)->take($limit)->get();
        } else {
            $tasks =\App\Models\Article::take($limit)->get();
        }

        $next = $tasks->last()->id;
        $cursor = new \League\Fractal\Pagination\Cursor($current, $previous, $next, $tasks->count());

        $resource = new \League\Fractal\Resource\Collection($tasks, new \App\Transformers\ArticleTransformer());
        $resource->setCursor($cursor);

        $fractal = new \League\Fractal\Manager();
        return $fractal->createData($resource)->toJson();
    });

    //dinggo 异常处理
    $api->post('task/exception', function () {
        $task = \App\Models\Article::find(2);


            throw new \Symfony\Component\HttpKernel\Exception\ConflictHttpException('Task was updated prior to your request.');


        // No error, we can continue to update the user as per usual.
    });

    $api->post('validate', function () {
        $rules = [
            'text' => ['required', 'string'],
            'is_completed' => ['required', 'boolean']
        ];

        $payload = app('request')->only('text', 'is_completed');

        $validator = app('validator')->make($payload, $rules);

        if ($validator->fails()) {
            throw new \Dingo\Api\Exception\StoreResourceFailedException('Could not create new task.', $validator->errors());
        }

        // Create user as per usual.
    });




    //$api->get('articles', 'App\Http\Api\Auth\ArticleController@show'); //使用 header Accept  application/vnd.myapp.v1+json   (Accept: application/API_STANDARDS_TREE.API_SUBTYPE.API_VERSION+json)
    $api->group(['middleware' => 'auth:api'], function ($api) {  //使用了Jwt-auth验证中间件来验证api优先级提前
        $api->get('logout', 'App\Http\Api\Auth\LoginController@logout');
        $api->get('articles', 'App\Http\Api\Auth\ArticleController@show');
        $api->get('refresh', 'App\Http\Api\Auth\RefreshController@refresh');
    });
});

$api->version('v2', function ($api) {
    $api->get('version', function () {
        return response('this is version v2');
    });
    $api->get('task/{id}', 'App\Http\Api\Auth\TaskController@show');
    $api->get('articles', 'App\Http\Api\Auth\ArticleController@index');
});


//Dingo Api 认证
$api->version('v3', function ($api) {
    $api->group(['middleware' => ['client.credentials']], function ($api) {   //密码模式
    $api->get('articles', 'App\Http\Api\Auth\ArticleController@index');
    });
    $api->post('user/auth', function () {
        $credentials = app('request')->only('email', 'password');
        try {
            if (! $token = \Tymon\JWTAuth\Facades\JWTAuth::attempt($credentials)) {
                throw new \Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException('Invalid credentials');
            }
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            throw new \Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException('Create token failed');
        }

        return compact('token');
    });

    $api->post('user/token', function () {
        app('request')->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        $http = new \GuzzleHttp\Client();
        // 发送相关字段到后端应用获取授权令牌
        $response = $http->post(route('passport.token'), [
            'form_params' => [
                'grant_type' => 'password',
                'client_id' => env('CLIENT_ID'),
                'client_secret' => env('CLIENT_SECRET'),
                'username' => app('request')->input('email'),  // 这里传递的是邮箱
                'password' => app('request')->input('password'), // 传递密码信息
                'scope' => '*'
            ],
        ]);

        return response()->json($response->getBody()->getContents());
    });
});


// 为了方便测试，我们先忽略 CSRF 校验
\Laravel\Passport\Passport::$ignoreCsrfToken = true;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    $user = \Auth::guard('api')->user();
    return response()->json($user);
});


