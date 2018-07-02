<?php

namespace App\Http\Controllers;
use Validator;
use App\Services;
use App\ServiceGalery;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class ServicesController  extends Controller
{
    public function store(Request $request)
    {
      if($request->id == null)
        $service = new Services();
      else
        $service = Services::find($request->id);
      $validatedData = Validator::make($request->all(),[
          'title' => 'required|max:191',
          'gallery' => 'array',
          'gallery.*.id' => 'exists:service_galeries,id'
      ]);
      if ($validatedData->fails()) {
          return response()->json($validatedData->errors(),400);
      }
      if(strtolower($service->title) != strtolower($request->get('title'))){
          $validatedData = Validator::make($request->all(),[
              'title' => 'required|unique:services',
          ]);
          if ($validatedData->fails()) {
              return response()->json($validatedData->errors(),400);
          }
      }

      

      $service->title=$request->title;
      if($request->id == null){
        $string=strip_tags($request->title);
        // Replace special characters with white space
        $string=preg_replace('/[^A-Za-z0-9-]+/', ' ', $string);
        // Trim White Spaces and both sides
        $string=trim($string);
        // Replace whitespaces with Hyphen (-) 
        $string=preg_replace('/[^A-Za-z0-9-]+/','-', $string); 
        // Conver final string to lowercase
        $slug=strtolower($string);
        $service->slug=$slug;
      }
      $service->description = $request->description;
      $service->shortDescription = $request->shortDescription;

      if($request->file('image') instanceof UploadedFile):
            $imageName=null;
          $image = $request->file('image');
          $imageName = uniqid().'services.'.$image->getClientOriginalExtension();
          $destinationPath = public_path('/uploads/services');
          $image->move($destinationPath, $imageName);
          $imageName = "/uploads/services/".$imageName;
          $service->featuredImage= $imageName;
      endif;
      $service->save();
      if(is_array($request->gallery)):
        foreach($request->gallery as $gimage){
            if(!array_key_exists('id',$gimage)):
                $gimage['id']=null;
            endif;
            if($gimage['id']==null){
                if($gimage['image'] instanceof UploadedFile):
                    $galeeryObj = new ServiceGalery();
                    $galeeryObj->title=$service->title;
                    $image = $gimage['image'];
                    $imageName = uniqid().'gallery.'.$image->getClientOriginalExtension();
                    $destinationPath = public_path('/uploads/services/gallery');
                    $image->move($destinationPath, $imageName);
                    $imageName = "/uploads/services/gallery/".$imageName;
                    $galeeryObj->image=$imageName;
                    $galeeryObj->services_id=$service->id;
                    $galeeryObj->save();
                endif;
            }else{
                $galeeryObj = ServiceGalery::find($gimage['id']);
                $galeeryObj->title=$service->title;
                if($gimage['image'] instanceof UploadedFile):
                    $image = $gimage['image'];
                    $imageName = uniqid().'gallery.'.$image->getClientOriginalExtension();
                    $destinationPath = public_path('/uploads/services/gallery');
                    $image->move($destinationPath, $imageName);
                    $imageName = "/uploads/services/gallery/".$imageName;
                    $galeeryObj->image=$imageName;
                endif;
                $galeeryObj->save();
            }
        }
    endif;
      $services = Services::where('id','=',$service->id)->with('service_galeries')->get()[0];
      return response()->json($services, 200);  
    }

    public function show($id){
        $service= Services::where('id','=',$id)->with('service_galeries')->get()[0];
        return response()->json($service, 200);
    }

    public function index()
    {
      $service= Services::with('service_galeries')->get()->toArray(); 
     return response()->json($service, 200);
    } 
}