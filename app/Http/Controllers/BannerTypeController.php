<?php

namespace App\Http\Controllers;

use Validator;
Use App\BannerType;
use Illuminate\Http\Request;

class BannerTypeController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = Validator::make($request->all(),[
            'typeName' => 'required|unique:banner_types|max:191',
        ]);
        if ($validatedData->fails()) {
            return response()->json($validatedData->errors(),400);
        }
        $bannerTypes = BannerType::create([
            'typeName'=>$bannerType['typeName']= $request->typeName,
        ]);

        $status= response()->json($bannerTypes, 200);
        if($status)							
        {
            $type= BannerType::where('id',$bannerTypes->id)->get()->toArray();
            $data = array('success' =>true, 'message' => 'Success! Banner Type created successfully.','data'=>$type[0]);
            echo json_encode($data);
        }
        else
        {
            return response("Category update failed. Please try again",500);
        }
    }

    

    public function update(Request $request, $id)
    {
    
        $bannerType = BannerType::find($id);
        $validatedData = Validator::make($request->all(),[
            'typeName' => 'required|max:191',
        ]);
        if ($validatedData->fails()) {
            return response()->json($validatedData->errors(),400);
        }
        if($bannerType->typeName != $request->get('typeName')){
            $validatedData = Validator::make($request->all(),[
                'typeName' => 'required|unique:banner_types|max:191',
            ]);
            if ($validatedData->fails()) {
                return response()->json($validatedData->errors(),400);
            }
        }

        $bannerType->typeName = $request->get('typeName');
        $bannerTypeResponse= $bannerType->save();
        $status= response()->json($bannerTypeResponse, 200);
        if($status)							
        {
            $type= BannerType::where('id',$bannerType->id)->get()->toArray();
            $data = array('success' =>true, 'message' => 'Success! Banner Type updated successfully.','data'=>$type[0]);
            echo json_encode($data);
        }
        else
        {
            return response("Category update failed. Please try again",500);
        }
    } 

    public function index()
    {
      $bannerTypes= BannerType::all()->toArray(); 
     return response()->json($bannerTypes, 200);
    } 

    public function destroy($id)
    {
      $bannerType= BannerType::find($id);
      $bannerType->delete();
      return response()->json($bannerType->toArray(), 200);
    }
}
