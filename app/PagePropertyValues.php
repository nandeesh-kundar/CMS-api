<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PagePropertyValues extends Model
{
    protected $fillable = ['propertyValue'];    

    
    /**
     * A message belong to a user
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function pages()
    {
      return $this->belongsTo('App\Pages');
    } 
    
    /**
     * A message belong to a user
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function page_properties()
    {
      return $this->belongsTo('App\PageProperty');
    }  
}
