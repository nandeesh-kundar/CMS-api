<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
protected $fillable = ['title', 'image', 'description']; 

public function post_categories()
{
    return $this->belongsTo('App\PostCategory','post_categories_id');
}
}