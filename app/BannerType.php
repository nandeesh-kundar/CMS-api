<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BannerType extends Model
{
    protected $fillable = ['typeName'];  
    
    /**
     * Get the comments for the blog post.
     */
    public function banners()
    {
        return $this->hasMany('App\Banner');
    }
}
