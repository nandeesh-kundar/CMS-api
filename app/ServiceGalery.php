<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ServiceGalery extends Model
{
    protected $fillable = ['title', 'image'];


    public function services(){
        return $this->belongsTo('App\Services','services_id');
    }
}
