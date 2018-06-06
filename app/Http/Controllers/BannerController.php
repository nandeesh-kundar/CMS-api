<?php

namespace App\Http\Controllers;
Use App\Banner;
use App\BannerType;
use Illuminate\Http\Request;
use Validator;

class BannerController extends Controller
{
    public function store(Request $request)
    {
      $validatedData = Validator::make($request->all(),[
        'banner_types_id' => 'required|exists:banner_types,id',
        'title' => 'required|unique:banners|max:191',
      ]);
      if ($validatedData->fails()) {
          return response()->json($validatedData->errors());
      }
      //  return Article::create($request->all());

      $image = $request->file('bannerimg');
      $input['bannerimg'] = time().'.'.$image->getClientOriginalExtension();
      $destinationPath = public_path('/uploads');
      $image->move($destinationPath, $input['bannerimg']);

      $bannerType = BannerType::find($request->banner_types_id);
      $banners = $bannerType->banners()->create([
        'title'=>$banner['title']= $request->title,
        'description'=>$banner['description']= $request->description,
        'bannerimg'=>$banner['bannerimg']= $input['bannerimg']
      ]);

      $status= response()->json($banners, 200);
      if($status)							
      {
      // $data = array('success' =>true, 'img_url'=>$input['bannerimg'], 'message' => 'Thanks! We have received your message.');
        $data = array('success' =>true, 'img_url'=>$input['bannerimg'], 'message' => 'Thanks! We have received your message.');
        echo json_encode($data);
      }
      else
      {
        //$response = 'There is an Error';
        //$myJSON = json_encode($response);
        //echo $myJSON;
        $data = array('success' =>false, 'message' => 'There is some error in recieving message.');
        echo json_encode($data);
      }
    }  

    public function show($id)
    {
      $banners= Banner::findOrFail();
      $status= response()->json($banners, 200);
   
    }  
    public function index()
    {
      $banners= Banner::all()->toArray(); 
      return $banner_response = response()->json($banners, 200);
    }

    public function update(Request $request, $id)
    {
    
      $banners = Banner::find($id);
      $validatedData = Validator::make($request->all(),[
        'banner_types_id' => 'required|exists:banner_types,id',
        'title' => 'required|max:191',
      ]);
      if ($validatedData->fails()) {
          return response()->json($validatedData->errors());
      }
      if($banners->title != $request->get('title')){
        $validatedData = Validator::make($request->all(),[
          'title' => 'unique:banners',
        ]);
        if ($validatedData->fails()) {
            return response()->json($validatedData->errors());
        }
      }
      $banners->description = $request->get('description');
      $banners->title = $request->get('title');
      $banners->banner_type_id = $request->get('banner_types_id');

      $image = $request->file('bannerimg');
      $input['bannerimg'] = time().'.'.$image->getClientOriginalExtension();
      $destinationPath = public_path('/uploads');
      $image->move($destinationPath, $input['bannerimg']);
      $banners->bannerimg = $input['bannerimg'];
      
      $ss= $banners->save();
      return $banner_edit = response()->json($banners->toArray(), 200);
    }   

    public function destroy($id)
    {
      $banner = Banner::find($id);
      $banner->delete();
      return response()->json($banner->toArray(), 200);
    }
}