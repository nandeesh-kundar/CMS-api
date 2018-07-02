<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Services extends Model
{
    protected $fillable = ['title', 'slug', 'shortDescription', 'description', 'featuredImage'];

    

    /**
     * Get the comments for the blog post.
     */
    public function service_galeries()
    {
        return $this->hasMany('App\ServiceGalery','services_id');
    }
}
