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
      $validatedData = Validator::make($request->all(),[
        'post_categories_id' => 'required|exists:post_categories,id',
        'title' => 'required|unique:posts|max:191',
      ]);
      if ($validatedData->fails()) 
      {
          return response()->json($validatedData->errors());
      }
      $image = $request->file('image');
      $input['image'] = time().'.'.$image->getClientOriginalExtension();
      $destinationPath = public_path('/uploads');
      $image->move($destinationPath, $input['image']);

      $posts = PostCategory::find($request->post_categories_id);
      $postTypes = $posts->posts()->create([
        'title'=>$post['title']= $request->title,
        'description'=>$post['description']= $request->description,
        'image'=>$post['image']= $input['image']
      ]);
      $status= response()->json($postTypes, 200);
      if($status)							
      {
        $data = array('success' =>true, 'img_url'=>$input['image'], 'message' => 'Thanks! We have received your message.');
        echo json_encode($data);
      }
      else
      {
        $data = array('success' =>false, 'message' => 'There is some error in recieving message.');
        echo json_encode($data);
      }
    }

    public function show($id)
    {
      $posts= Post::all()->toArray();
     return response()->json($posts, 200);
    }  

    public function index()
    {
      $posts= Post::with('post_categories')->get()->toArray(); 
      return response()->json($posts, 200);
    }

    
    public function update(Request $request, $id)
    {
    
        $posts = Post::find($id);
      $validatedData = Validator::make($request->all(),[
        'post_categories_id' => 'required|exists:post_categories,id',
        'title' => 'required|max:191',
      ]);
      if ($validatedData->fails()) {
          return response()->json($validatedData->errors());
      }
      if($posts->title != $request->get('title')){
        $validatedData = Validator::make($request->all(),[
          'title' => 'unique:posts',
        ]);
        if ($validatedData->fails()) {
            return response()->json($validatedData->errors());
        }
      }
      $posts->description = $request->get('description');
      $posts->title = $request->get('title');
      $posts->post_categories_id = $request->get('post_categories_id');

      $image = $request->file('image');
      $input['image'] = time().'.'.$image->getClientOriginalExtension();
      $destinationPath = public_path('/uploads');
      $image->move($destinationPath, $input['image']);
      $posts->bannerimg = $input['image'];
      
      $post_save= $posts->save();
      return $post_edit = response()->json($posts->toArray(), 200);

    }   
    public function destroy($id)
    {
      $posts = Post::find($id);
      $posts->delete();
      return response()->json($posts->toArray(), 200);
    }
}