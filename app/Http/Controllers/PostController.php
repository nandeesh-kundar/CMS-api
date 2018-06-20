<?php

namespace App\Http\Controllers;
use Validator;
Use App\PostCategory;
Use App\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function store(Request $request)
    {
      if($request->id == null)
        $post = new Post();
      else
        $post = Post::find($request->id);

      $validatedData = Validator::make($request->all(),[
          'title' => 'required|max:191',
          'categories' => 'array',
          'categories.*.id' => 'exists:post_categories,id'
      ]);
      if ($validatedData->fails()) {
          return response()->json($validatedData->errors(),400);
      }
      if($post->title != $request->get('title')){
          $validatedData = Validator::make($request->all(),[
              'title' => 'required|unique:posts',
          ]);
          if ($validatedData->fails()) {
              return response()->json($validatedData->errors(),400);
          }
      }

      $post->title=$request->title;
      $post->description = $request->description;
      
      $imageName=null;
      if($request->file('image') != null):
          $image = $request->file('image');
          $imageName = uniqid().'cat.'.$image->getClientOriginalExtension();
          $destinationPath = public_path('/uploads/post');
          $image->move($destinationPath, $imageName);
          $imageName = "/uploads/cat/".$imageName;
      endif;
      $post->image= $imageName;
      
      try{
        $post->save();
        $post->post_categories()->sync([1,2]);
        return response()->json($post, 200);  
      }catch(\Exception $e){
          return response("Post update failed. Please try again",500);
      }
    }

    public function show($id){
      return Post::find($id)->with('post_categories')->get();
    }
}