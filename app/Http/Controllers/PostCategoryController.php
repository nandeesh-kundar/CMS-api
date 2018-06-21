<?php

namespace App\Http\Controllers;
use Validator;
Use App\PostCategory;
use Illuminate\Http\Request;

class PostCategoryController extends Controller
{
    public function store(Request $request)
    {
        if($request->id != null)
            $postCategory = PostCategory::find($request->id);
        else   
            $postCategory = new PostCategory();
        $validatedData = Validator::make($request->all(),[
            'title' => 'required|max:191',
        ]);
        if ($validatedData->fails()) {
            return response()->json($validatedData->errors(),400);
        }
        if($postCategory->title != $request->get('title')){
            $validatedData = Validator::make($request->all(),[
                'title' => 'required|unique:post_categories|max:191',
            ]);
            if ($validatedData->fails()) {
                return response()->json($validatedData->errors(),400);
            }
        }
        $imageName=null;
        if($request->image != null):
            $image = $request->file('image');
            $imageName = uniqid().'cat.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/uploads/cat');
            $image->move($destinationPath, $imageName);
            $imageName = "/uploads/cat/".$imageName;
        endif;
        $postCategory->title = $request->title;
        $postCategory->description = $request->description;
        $postCategory->image= $imageName;

        try{
            $postCategory->save();
            return response()->json($postCategory, 200);  
        }catch(\Exception $e){
            return response("Category update failed. Please try again",500);
        }
    }

    public function index()
    {
      $postCategories= PostCategory::all()->toArray(); 
     return response()->json($postCategories, 200);
    } 
    
    public function destroy($id)
    {
        $postCategories= PostCategory::find($id);
        $postCategories->delete();
        return response()->json($postCategories->toArray(), 200);
    }
}