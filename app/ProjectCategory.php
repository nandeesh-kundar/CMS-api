<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProjectCategory extends Model
{
    protected $fillable = ['title', 'image', 'description']; 


    public function projects()
    {
        return $this->belongsToMany('App\Project','project_categories_union');
    }
}