<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use App\PageProperty;
use Validator;

class PagePropertyController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = Validator::make($request->all(),[
            'propertyKey' => 'required|unique:page_properties|max:191',
            'propertyType' => 'required|in:string,text,number,file',
        ]);
        if ($validatedData->fails()) {
            return response()->json($validatedData->errors());
        }
        $pageProperties = PageProperty::create(
          ['propertyKey'=>$pageProperty['propertyKey']= $request->propertyKey,
           'propertyType'=>$pageProperty['propertyType']= $request->propertyType,
        ]);

        $status= response()->json($pageProperties, 200);
        if($status)							
        {
            $data = array('success' =>true, 'message' => 'Success! Page property created successfully.');
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
    
        $pageProperty = PageProperty::find($id);
        $validatedData = Validator::make($request->all(),[
            'propertyKey' => 'required|max:191',
            'propertyType' => 'required|in:string,text,number,file',
        ]);
        if ($validatedData->fails()) {
            return response()->json($validatedData->errors());
        }
        if($pageProperty->propertyKey != $request->get('propertyKey')){
            $validatedData = Validator::make($request->all(),[
                'propertyKey' => 'required|unique:page_properties|max:191'
            ]);
            if ($validatedData->fails()) {
                return response()->json($validatedData->errors());
            }
        }

        $pageProperty->propertyKey = $request->get('propertyKey');
        $pageProperty->propertyType = $request->get('propertyType');
        $pageResponse= $pageProperty->save();
        return response()->json($pageProperty->toArray(), 200);
    } 

    public function index()
    {
        $pageProperty= PageProperty::all()->toArray(); 
        return response()->json($pageProperty, 200);
    }  
}