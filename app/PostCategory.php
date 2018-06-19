<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PostCategory extends Model
{
    protected $fillable = ['title', 'image', 'description']; 


    public function posts()
    {
        return $this->hasMany('App\Post','post_categories_id');
    }
}