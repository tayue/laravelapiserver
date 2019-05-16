<?php

namespace App\Http\Api\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Article;
use App\Http\Resources\ArticleResource;
class ArticleController extends Controller
{
    public function index()
    {
        return Article::all();
    }

    /**
     * @param Article $article
     * @return ArticleResource
     */
    public function show(Article $article)
    {


        @$article=Article::where('id', '=',1)->get()->pop();

        ArticleResource::withoutWrapping();
        return new ArticleResource($article);


     //   return new ArticleResource($article);


    }

    public function store(Request $request)
    {
        $article = Article::create($request->all());

        return response()->json($article, 201);
    }

    public function update(Request $request, Article $article)
    {
        $article->update($request->all());

        return response()->json($article, 200);
    }

    public function delete(Article $article)
    {
        $article->delete();

        return response()->json(null, 204);
    }
}
