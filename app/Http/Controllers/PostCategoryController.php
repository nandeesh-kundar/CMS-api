<?php

namespace App\Http\Controllers;
use Validator;
Use App\PostCategory;
use Illuminate\Http\Request;

class PostCategoryController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = Validator::make($request->all(),[
        'title' => 'required|unique:post_categories|max:191',        
            ]);

         if ($validatedData->fails()) 
         {
             return response()->json($validatedData->errors(),400);
         }

         $image = $request->file('image');
         $input['image'] = time().'.'.$image->getClientOriginalExtension();
         $destinationPath = public_path('/uploads');
         $image->move($destinationPath, $input['image']);

         $postCategories = PostCategory::create([
            'title'=>$postCategory['title']= $request->title,
            'description'=>$postCategory['description']= $request->description,
            'image'=>$postCategory['image']= $input['image']
        ]);  
        $status= response()->json($postCategories, 200);    
        if($status)							
        {
            $data = array('success' =>true, 'img_url'=>$input['image'], 'message' => 'Success! Post Category created successfully.');
            echo json_encode($data);
        }
        else
        {
            return response("Category update failed. Please try again",500);
        }
    }
    public function update(Request $request, $id)
    {
        $postCategory = PostCategory::find($id);
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

        $image = $request->file('image');
        $input['image'] = time().'.'.$image->getClientOriginalExtension();
        $destinationPath = public_path('/uploads');
        $image->move($destinationPath, $input['image']);

        $postCategory->title = $request->get('title');
        $postCategory->description = $request->get('description');
        $postCategory->image = $input['image'];
        $postCategorysave= $postCategory->save();
        $status= response()->json($postCategorysave, 200);  

        if($status)							
        {
            $data = array('success' =>true, 'img_url'=>$input['image'], 'message' => 'Success! Post Category updated successfully.');
            echo json_encode($data);
        }
        else
        {
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