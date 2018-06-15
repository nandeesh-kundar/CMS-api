<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PageSectionProp extends Model
{
    protected $fillable = ['value'];

    public function page_sections(){
        return $this->belongsTo('App\PageSection');
    }
}
