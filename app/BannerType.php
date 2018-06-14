<?php

namespace App;

use App\Banner;
use App\BannerType;
use Illuminate\Database\Eloquent\Model;

class BannerType extends Model
{
    protected $fillable = ['typeName'];  
    
    /**
     * Get the comments for the blog post.
     */
    public function banners()
    {
        return $this->hasMany(Banner::class, 'banner_types_id');
    }

    public static function boot ()
    {
        parent::boot();

        self::deleting(function (BannerType $bannerType) {

            foreach ($bannerType->banners as $banner)
            {
                $banner->delete();
            }
        });
    }
}
