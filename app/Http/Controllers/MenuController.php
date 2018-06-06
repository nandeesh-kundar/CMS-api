<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = Validator::make($request->all(),[
            'title' => 'required|unique:menus|max:191',
            'linkType' => 'required|in:custom,page',
        ]);
        if ($validatedData->fails()) {
            return response()->json($validatedData->errors());
        }
        if($request->linkType == 'custom'){
            $validatedData = Validator::make($request->all(),[
                'customLink' => 'required',
            ]);
            if ($validatedData->fails()) {
                return response()->json($validatedData->errors());
            }
        }else{
            $validatedData = Validator::make($request->all(),[
                'pageSlug' => 'required',
            ]);
            if ($validatedData->fails()) {
                return response()->json($validatedData->errors());
            }
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
}
