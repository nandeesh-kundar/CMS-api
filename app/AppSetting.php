<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AppSetting extends Model
{
    protected $fillable = ['companyName', 'logo', 'description', 'primaryPhone', 'secondaryPhone', 'primaryEmail', 'secondaryEmail', 'primaryAddress', 'secondaryAddress', 'facebookLink', 'twitterLink', 'instaLink', 'googleLink', 'whatsAppLink','youtubeLink', 'footerMessage','embedMap', 'longitude', 'latitude'];
}
