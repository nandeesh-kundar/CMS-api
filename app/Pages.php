<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pages extends Model
{
    protected $fillable = ['title', 'slug', 'description'];


    /**
     * Get the comments for the blog post.
     */
    public function page_sections()
    {
        return $this->hasMany('App\PageSection');
    }
}
