<?php

namespace App\Http\Api\Auth;
use App\Http\Requests\ArticleFormRequest;
use App\Models\Article;
use App\Transformers\ArticleTransformer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Api\Auth\ApiController;
// 引入命名空间
use League\Fractal\Serializer\JsonApiSerializer;

class TaskController extends ApiController
{


    public function user(Request $request)
    {
        if ($request->bearerToken()) {
            return $this->authenticateViaBearerToken($request);
        } elseif ($request->cookie(Passport::cookie())) {
            return $this->authenticateViaCookie($request);
        }
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //collection 集合演示
        //$tasks = Article::all();
        //return $this->response->collection($tasks, new ArticleTransformer());
        //分页演示
        $tasks = Article::paginate();
        return $this->response->paginator($tasks, new ArticleTransformer());


    }

    public function page(Request $request)
    {
        $current = $request->input('current',0);
        $previous = $request->input('previous',0);
        $limit = $request->input('limit') ? : 10;
        if ($current) {
            $tasks = Article::where('id', '>', $current)->take($limit)->get();
        } else {
            $tasks = Article::take($limit)->get();
        }

        $next = $tasks->last()->id;
        $cursor = new \League\Fractal\Pagination\Cursor($current, $previous, $next, $tasks->count());

        return $this->response->collection($tasks, new ArticleTransformer, [], function ($resource, $fractal) use ($cursor) {
            $resource->setCursor($cursor);
        });
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ArticleFormRequest $request)
    {

        // 表单验证成功，继续后续处理
        return $this->response->errorUnauthorized();

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {  //使用关联模型的话多加一个参数（?include=comment）
        //$user=User::firstOrFail(1);
       // @$article=Article::where('id', '=',$id)->get()->pop();
       // var_dump($article);
       // @$article=Article::firstOrFail($id);
        //$article=$article->toArray();

        //return $this->response->item($article, new ArticleTransformer());
        //return $this->response->item($article, new ArticleTransformer())->withHeader('Foo', 'Bar'); //添加响应头
//        return $this->response->item($article, new ArticleTransformer())->withHeaders([   //添加多个响应头
//            'Foo' => 'Bar',
//            'Hello' => 'World'
//        ]);

//        $cookie = new \Symfony\Component\HttpFoundation\Cookie('foo', 'bar'); //添加Cookie
//        return $this->response->item($article, new ArticleTransformer())->withCookie($cookie);

//        return $this->response->item($article, new ArticleTransformer)->setStatusCode(201); //设置响应状态码

        //return $this->response->item($article, new ArticleTransformer)->addMeta('foo', 'bar');  //添加元数据

//        $meta = [
//            'foo' => 'bar'
//        ];
//        return $this->response->item($task, new TaskTransformer())->setMeta($meta);

        $article = Article::findOrFail($id);
        return $this->response->item($article, new ArticleTransformer(), ['key' => 'article'], function ($resource, $fractal) {
            $fractal->setSerializer(new JsonApiSerializer());
        });


       // return $this->response->array($article);
        //return $article;

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
