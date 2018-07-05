<?php

namespace App\Http\Controllers;
use Validator;
use App\Partners;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class PartnersController  extends Controller
{
    public function store(Request $request)
    {
      if($request->id == null)
        $partner = new Partners();
      else
        $partner = Partners::find($request->id);
      $validatedData = Validator::make($request->all(),[
          'title' => 'required|max:191',
          'image' => 'required',
      ]);
      if ($validatedData->fails()) {
          return response()->json($validatedData->errors(),400);
      }
      if(strtolower($partner->title) != strtolower($request->get('title'))){
          $validatedData = Validator::make($request->all(),[
              'title' => 'required|unique:services',
          ]);
          if ($validatedData->fails()) {
              return response()->json($validatedData->errors(),400);
          }
      }

      

      $partner->title=$request->title;
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
        $partner->slug=$slug;
      }
      $partner->description = $request->description;
      $partner->shortDescription = $request->shortDescription;

      if($request->file('image') instanceof UploadedFile):
            $imageName=null;
            $image = $request->file('image');
            $imageName = uniqid().'services.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/uploads/services');
            $image->move($destinationPath, $imageName);
            $imageName = "/uploads/services/".$imageName;
            $partner->featuredImage= $imageName;
      endif;
      $partner->save();
      $partners = Partners::where('id','=',$partner->id)->get()[0];
      return response()->json($partners, 200);  
    }

    public function show($id){
        $partner= Partners::where('id','=',$id)->get()[0];
        return response()->json($partner, 200);
    }

    public function index()
    {
      $partner= Partners::orderBy('id', 'DESC')->get()->toArray(); 
     return response()->json($partner, 200);
    } 

    public function destroy($id)
    {
        $partner=Partners::find($id);    
        $partner->delete();
        return response()->json("Successfully deleted", 200);
    }
}