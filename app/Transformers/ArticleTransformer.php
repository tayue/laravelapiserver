<?php
/**
 * Created by PhpStorm.
 * User: hdeng
 * Date: 2019/5/16
 * Time: 10:04
 */

namespace App\Transformers;

use App\Models\Article;
use League\Fractal\TransformerAbstract;
use App\Transformers\CommentTransformer;

class ArticleTransformer extends TransformerAbstract
{

    protected $availableIncludes = ['comment'];

    public function transform(Article $article)
    {
        return [
            'id' => $article->id,
            'title' => $article->title,
            'body' => $article->body
        ];
    }

    public function includeComment(Article $article)
    {
        $comment = $article->comment; //调用关联模型方法comment
        return $this->item($comment, new CommentTransformer());
    }
}
