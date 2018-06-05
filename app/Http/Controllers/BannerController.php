<?php

namespace App\Http\Controllers;
Use App\Banner;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    public function store(Request $request)
    {
      //  return Article::create($request->all());

      //$image = $request->file('bannerimg');
    //  $input['bannerimg'] = time().'.'.$image->getClientOriginalExtension();
     // $destinationPath = public_path('/uploads');
     // $image->move($destinationPath, $input['bannerimg']);

      $banners = Banner::create(['title'=>$banner['title']= $request->title,
      'description'=>$banner['description']= $request->description,
       'bannerimg'=>$banner['bannerimg']= $input['bannerimg'],
    ]);

    $status= response()->json($banners, 200);
      if($status)							
		{
     // $data = array('success' =>true, 'img_url'=>$input['bannerimg'], 'message' => 'Thanks! We have received your message.');
      $data = array('success' =>true, 'message' => 'Thanks! We have received your message.');
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
       $banners->description = $request->get('description');
       $banners->title = $request->get('title');
       $banners->bannerimg = $request->get('bannerimg');
    //   $image=$request->file('bannerimg');
      // $getimageName = time().'.'.$image->getClientOriginalExtension();
     //  $request->image->move(public_path('uploads'), $getimageName);
         
      // $product->image =$getimageName; 
      // Same as above
      // $banners['bannerimg'] =$getimageName;
       $ss= $banners->save();
       return $banner_edit = response()->json($banners->toArray(), 200);
    }   

    public function destroy($id)
    {
      $banner = Banner::find($id);
      $banner->delete();
      return $banner_destroy = response()->json($banner->toArray(), 200);
    }
}