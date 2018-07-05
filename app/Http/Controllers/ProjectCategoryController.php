<?php

namespace App\Http\Controllers;
use Validator;
Use App\ProjectCategory;
use Illuminate\Http\Request;

class ProjectCategoryController extends Controller
{
    public function store(Request $request)
    {
        if($request->id != null)
            $projectCategory = ProjectCategory::find($request->id);
        else   
            $projectCategory = new ProjectCategory();
        $validatedData = Validator::make($request->all(),[
            'title' => 'required|max:191',
        ]);
        if ($validatedData->fails()) {
            return response()->json($validatedData->errors(),400);
        }
        if($projectCategory->title != $request->get('title')){
            $validatedData = Validator::make($request->all(),[
                'title' => 'required|unique:project_categories|max:191',
            ]);
            if ($validatedData->fails()) {
                return response()->json($validatedData->errors(),400);
            }
        }
        $imageName=null; 
        if($request->image != null):
            $image = $request->image;
            $imageName = uniqid().'cat.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/uploads/cat');
            $image->move($destinationPath, $imageName);
            $imageName = "/uploads/cat/".$imageName;
            $projectCategory->image= $imageName;
        endif;
        $projectCategory->title = $request->title;
        $projectCategory->description = $request->description;

        try{
            $projectCategory->save();
            return response()->json($projectCategory, 200);  
        }catch(\Exception $e){
            return response("Category update failed. Please try again",500);
        }
    }

    public function index()
    {
      $projectCategories= ProjectCategory::with('projects')->get()->toArray(); 
     return response()->json($projectCategories, 200);
    } 
    
    public function show($id)
    {
        $projectCategories= ProjectCategory::find($id);
        return response()->json($projectCategories->toArray(), 200);
    }
    
    public function destroy($id)
    {
        $projectCategories= ProjectCategory::find($id);
       // $projectCategories->delete();
        return response()->json($projectCategories->toArray(), 200);
    }
}