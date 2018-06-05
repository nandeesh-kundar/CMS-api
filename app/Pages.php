<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pages extends Model
{
    protected $fillable = ['title', 'description'];

    
    /**
     * Get the comments for the blog post.
     */
    public function page_property_values()
    {
        return $this->hasMany('App\PagePropertyValues');
    }
}
