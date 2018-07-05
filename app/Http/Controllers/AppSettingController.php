<?php

namespace App\Http\Controllers;
use Validator;
use App\AppSetting;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class AppSettingController  extends Controller
{
    public function store(Request $request)
    {
        $setting = AppSetting::find(999);
        if(!($setting instanceof AppSetting)){
            $setting=new AppSetting();
            $setting->id = 999;
        }
        $setting->companyName=$request->companyName;
        $setting->description=$request->description;
        $setting->primaryPhone=$request->primaryPhone;
        $setting->secondaryPhone=$request->secondaryPhone;
        $setting->primaryEmail=$request->primaryEmail;
        $setting->secondaryEmail=$request->secondaryEmail;
        $setting->primaryAddress=$request->primaryAddress;
        $setting->secondaryAddress=$request->secondaryAddress;
        $setting->facebookLink=$request->facebookLink;
        $setting->twitterLink=$request->twitterLink;
        $setting->instaLink=$request->instaLink;
        $setting->googleLink=$request->googleLink;
        $setting->whatsAppLink=$request->whatsAppLink;
        $setting->youtubeLink=$request->youtubeLink;
        $setting->footerMessage=$request->footerMessage;
        $setting->embedMap=$request->embedMap;
        $setting->longitude=$request->longitude;
        $setting->latitude=$request->latitude;

        
        if($request->file('logo') instanceof UploadedFile):
                $imageName=null;
                $image = $request->file('logo');
                $imageName = uniqid().'main.'.$image->getClientOriginalExtension();
                $destinationPath = public_path('/uploads/main');
                $image->move($destinationPath, $imageName);
                $imageName = "/uploads/main/".$imageName;
                $setting->logo= $imageName;
        endif;

        $setting->save();
        return response()->json("DATA UPDATED", 200); 
    }

    public function index(){
        $setting= AppSetting::find(999);
        return response()->json($setting, 200);
    }
}