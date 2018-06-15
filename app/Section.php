<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    protected $fillable = ['title'];

    /**
     * Get the comments for the blog post.
     */
    public function section_properties()
    {
        return $this->hasMany('App\SectionProperties');
    }

    /**
     * Get the comments for the blog post.
     */
    public function page_sections()
    {
        return $this->hasMany('App\PageSection');
    }


}
