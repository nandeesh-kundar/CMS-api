<?php

namespace App\Http\Controllers;
use Validator;
use App\Teams;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class TeamsController  extends Controller
{
    public function store(Request $request)
    {
      if($request->id == null)
        $team = new Teams();
      else
        $team = Teams::find($request->id);
      $validatedData = Validator::make($request->all(),[
          'title' => 'required|max:191',
          'image' => 'required',
      ]);
      if ($validatedData->fails()) {
          return response()->json($validatedData->errors(),400);
      }
      if(strtolower($team->title) != strtolower($request->get('title'))){
          $validatedData = Validator::make($request->all(),[
              'title' => 'required|unique:services',
          ]);
          if ($validatedData->fails()) {
              return response()->json($validatedData->errors(),400);
          }
      }

      

      $team->title=$request->title;
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
        $team->slug=$slug;
      }
      $team->description = $request->description;
      $team->shortDescription = $request->shortDescription;

      if($request->file('image') instanceof UploadedFile):
            $imageName=null;
            $image = $request->file('image');
            $imageName = uniqid().'services.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/uploads/services');
            $image->move($destinationPath, $imageName);
            $imageName = "/uploads/services/".$imageName;
            $team->featuredImage= $imageName;
      endif;
      $team->save();
      $teams = Teams::where('id','=',$team->id)->get()[0];
      return response()->json($teams, 200);  
    }

    public function show($id){
        $team= Teams::where('id','=',$id)->get()[0];
        return response()->json($team, 200);
    }

    public function index()
    {
      $team= Teams::orderBy('id', 'DESC')->get()->toArray(); 
     return response()->json($team, 200);
    } 

    public function destroy($id)
    {
        $team=Teams::find($id);    
        $team->delete();
        return response()->json("Successfully deleted", 200);
    }
}