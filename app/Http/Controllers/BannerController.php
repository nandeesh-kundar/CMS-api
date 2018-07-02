<?php

namespace App\Http\Controllers;
Use App\Banner;
use Validator;
use App\BannerType;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    public function store(Request $request)
    {

      $validatedData = Validator::make($request->all(),[
        'title' => 'required|max:191',
      ]);
      if ($validatedData->fails()) {
          return response()->json($validatedData->errors());
      }

      if($request->id != null){
        $banners = Banner::find($request->id);
      }else{
        $banners = new Banner();
        $validatedData = Validator::make($request->all(),[
          'banner_types_id' => 'required|exists:banner_types,id',
        ]);
        if ($validatedData->fails()) {
            return response()->json($validatedData->errors());
        }
        $banners->banner_types_id = $request->banner_types_id;
      }
      
      $banners->description = $request->get('description');
      $banners->title = $request->get('title');

      $image = $request->file('bannerimg');
      if($image != null){
        $input['bannerimg'] = time().'.'.$image->getClientOriginalExtension();
        $destinationPath = public_path('/uploads/banner');
        $image->move($destinationPath, $input['bannerimg']);
        $banners->bannerimg = '/uploads/banner/'.$input['bannerimg'];
      }
      
      $ss= $banners->save();
      return $banner_edit = response()->json($banners->toArray(), 200);
    }  

    public function show($title)
    {
      $banners=BannerType::with('banners')->where('typeName','=',$title)->get();
      if(sizeof($banners)>0){
        $banners=$banners[0]->banners;
      }
      //$banners= \DB::table('banners')->leftJoin('banner_types', 'banner_types.id', '=', 'banners.banner_types_id')->where('banner_types.typeName','=',$title)->select('banners.*')->get();
      $status= response()->json($banners, 200);
      return $status;
    }  

    public function index()
    {
      $banners= Banner::with('bannerTypes')->get()->toArray(); 
      return response()->json($banners, 200);
    }  

    public function destroy($id)
    {
      $banner = Banner::find($id);
      $banner->delete();
      return response()->json($banner->toArray(), 200);
    }
}