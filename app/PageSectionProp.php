<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PageSectionProp extends Model
{
    protected $fillable = ['value','link','type'];

    public function page_sections(){
        return $this->belongsTo('App\PageSection','ps_id');
    }

    public function section_properties(){
        return $this->belongsTo('App\SectionProperties','prop_id');
    }
}
