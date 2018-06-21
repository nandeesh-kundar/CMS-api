<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SectionProperties extends Model
{
    protected $fillable = ['key', 'type'];

    public function sections(){
        return $this->belongsTo('App\Section','section_id');
    }

    public function page_section_props()
    {
        return $this->hasMany('App\PageSectionProp','prop_id');
    }
    
}
