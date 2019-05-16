<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Comment;


/**
 * App\Models\Article
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Article newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Article newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Article query()
 * @mixin \Eloquent
 */
class Article extends Model
{
    protected $fillable = ['title', 'body'];


    //关联模型方法（一对一）
    public function comment()
    {
        return $this->hasOne(Comment::class, 'id', 'id');
    }


    //关联模型方法（一对多）
    public function comments()
    {
        return $this->hasMany(Comment::class, 'post_id', 'id');
    }
}
