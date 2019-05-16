<?php
/**
 * Created by PhpStorm.
 * User: hdeng
 * Date: 2019/5/16
 * Time: 11:08
 */

namespace App\Transformers;
use App\Models\Comment;
use League\Fractal\TransformerAbstract;

class CommentTransformer extends TransformerAbstract
{
    public function transform(Comment $comments)
    {

        return [
            'id' => $comments->id,
            'user_id' => $comments->user_id,
            'content' => $comments->content,
            'status' => $comments->status
        ];
    }
}
