<?php

namespace App\Http\Controllers;
use Validator;
use App\Testimonials;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class TestimonialsController  extends Controller
{
    public function store(Request $request)
    {
      if($request->id == null)
        $testimonial = new Testimonials();
      else
        $testimonial = Testimonials::find($request->id);
      $validatedData = Validator::make($request->all(),[
          'title' => 'required|max:191'
      ]);
      if ($validatedData->fails()) {
          return response()->json($validatedData->errors(),400);
      }
      if(strtolower($testimonial->title) != strtolower($request->get('title'))){
          $validatedData = Validator::make($request->all(),[
              'title' => 'required|unique:testimonials',
          ]);
          if ($validatedData->fails()) {
              return response()->json($validatedData->errors(),400);
          }
      }

      

      $testimonial->title=$request->title;
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
        $testimonial->slug=$slug;
      }
      $testimonial->description = $request->description;
      $testimonial->shortDescription = $request->shortDescription;

      if($request->file('image') instanceof UploadedFile):
            $imageName=null;
          $image = $request->file('image');
          $imageName = uniqid().'testimonials.'.$image->getClientOriginalExtension();
          $destinationPath = public_path('/uploads/testimonials');
          $image->move($destinationPath, $imageName);
          $imageName = "/uploads/testimonials/".$imageName;
          $testimonial->featuredImage= $imageName;
      endif;
      $testimonial->save();

      

      $testimonials = Testimonials::find($testimonial->id);
      return response()->json($testimonials, 200);  
    }

    public function show($id){
        $testimonial= Testimonials::find($id);
        return response()->json($testimonial, 200);
    }

    public function index()
    {
      $testimonial= Testimonials::all()->toArray(); 
     return response()->json($testimonial, 200);
    } 
}