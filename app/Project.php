<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = ['title', 'image', 'description']; 

    public function project_categories()
    {
        return $this->belongsToMany('App\ProjectCategory','project_categories_union','project_id', 'category_id');
    }
}