<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use App\BannerType;
use Validator;

class BannerTypeController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = Validator::make($request->all(),[
            'typeName' => 'required|unique:banner_types|max:191',
        ]);
        if ($validatedData->fails()) {
            return response()->json($validatedData->errors());
        }
        $bannerTypes = BannerType::create([
            'typeName'=>$bannerType['typeName']= $request->typeName,
        ]);

        $status= response()->json($bannerTypes, 200);
        if($status)							
        {
            $data = array('success' =>true, 'message' => 'Success! Banner Type created successfully.');
            echo json_encode($data);
        }
        else
        {
            $data = array('success' =>false, 'message' => 'Failed! Something went wrong. Please try again.');
            echo json_encode($data);
        }
    }

    

    public function update(Request $request, $id)
    {
    
        $bannerType = BannerType::find($id);
        $validatedData = Validator::make($request->all(),[
            'typeName' => 'required|max:191',
        ]);
        if ($validatedData->fails()) {
            return response()->json($validatedData->errors());
        }
        if($bannerType->typeName != $request->get('typeName')){
            $validatedData = Validator::make($request->all(),[
                'typeName' => 'required|unique:banner_types|max:191',
            ]);
            if ($validatedData->fails()) {
                return response()->json($validatedData->errors());
            }
        }

        $bannerType->typeName = $request->get('typeName');
        $bannerTypeResponse= $bannerType->save();
        return response()->json($bannerType->toArray(), 200);
    } 

    public function index()
    {
      $bannerTypes= BannerType::all()->toArray(); 
     return response()->json($bannerTypes, 200);
    } 
}
