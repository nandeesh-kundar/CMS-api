<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PageSection extends Model
{
    protected $fillable = ['title'];

    public function pages(){
        return $this->belongsTo('App\Pages');
    }

    public function sections(){
        return $this->belongsTo('App\Section','section_id');
    }

    public function page_section_props()
    {
        return $this->hasMany('App\PageSectionProp','ps_id');
    }
}
