<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Testimonials extends Model
{
    protected $fillable = ['title', 'slug', 'shortDescription', 'description', 'featuredImage'];

}
