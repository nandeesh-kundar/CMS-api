<?php

namespace App;

use App\BannerType;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $fillable = ['title', 'description', 'bannerimg'];

    /**
     * A message belong to a user
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function bannerTypes()
    {
      return $this->belongsTo(BannerType::class, 'banner_types_id');
    }    
}