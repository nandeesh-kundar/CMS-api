<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Teams extends Model
{
    protected $fillable = ['title', 'slug', 'shortDescription', 'description', 'featuredImage'];

    
}
