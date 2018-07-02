<?php

namespace App\Http\Controllers;
use Validator;
Use App\ProjectCategory;
Use App\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function store(Request $request)
    {
      if($request->id == null)
        $project = new Project();
      else
        $project = Project::find($request->id);
      $validatedData = Validator::make($request->all(),[
          'title' => 'required|max:191',
          'categories' => 'array',
          'categories.*.id' => 'exists:project_categories,id'
      ]);
      if ($validatedData->fails()) {
          return response()->json($validatedData->errors(),400);
      }
      if(strtolower($project->title) != strtolower($request->get('title'))){
          $validatedData = Validator::make($request->all(),[
              'title' => 'required|unique:projects',
          ]);
          if ($validatedData->fails()) {
              return response()->json($validatedData->errors(),400);
          }
      }

      $project->title=$request->title;
      $project->description = $request->description;
      
      $imageName=null;
      if($request->file('image') != null):
          $image = $request->file('image');
          $imageName = uniqid().'project.'.$image->getClientOriginalExtension();
          $destinationPath = public_path('/uploads/project');
          $image->move($destinationPath, $imageName);
          $imageName = "/uploads/project/".$imageName;
      endif;
      $project->image= $imageName;
      
      try{
        $project->save();
        $categoryArray=[];
        foreach($request->categories as $category){
            if(array_key_exists('selected',$category)){
                if($category['selected'] == true){
                    if(array_key_exists('id',$category)){
                        $categoryArray[]=$category['id'];
                    }
                }
            }
        }
        $project->project_categories()->sync($categoryArray);
        return response()->json($project, 200);  
      }catch(\Exception $e){
          return response("Project update failed. Please try again",500);
      }
    }

    public function show($id){
      return Project::with('project_categories')->where('id','=',$id)->get()[0];
    }

    public function index()
    {
      $project= Project::with('project_categories')->get()->toArray(); 
     return response()->json($project, 200);
    } 
}