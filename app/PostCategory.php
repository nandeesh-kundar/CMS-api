<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PostCategory extends Model
{
    protected $fillable = ['title', 'image', 'description']; 


    public function posts()
    {
        return $this->belongsToMany('App\Post','post_categories_union');
    }
}