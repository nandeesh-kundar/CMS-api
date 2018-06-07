<?php

namespace App\Http\Controllers;

use App\Pages;
use Validator;
use App\PageProperty;
use App\PagePropertyValues;
use Illuminate\Http\Request;

class PagesController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = Validator::make($request->all(),[
            'title' => 'required|unique:pages|max:191'
        ]);
        if ($validatedData->fails()) {
            return response()->json($validatedData->errors());
        }
        $string=strip_tags($request->title);
        // Replace special characters with white space
        $string=preg_replace('/[^A-Za-z0-9-]+/', ' ', $string);
        // Trim White Spaces and both sides
        $string=trim($string);
        // Replace whitespaces with Hyphen (-) 
        $string=preg_replace('/[^A-Za-z0-9-]+/','-', $string); 
        // Conver final string to lowercase
        $slug=strtolower($string);

        $files= $request->file('properties');
        $pages = Pages::create([
            'title'=> $request->title,
            'slug'=> $slug,
            'description'=> $request->description,
        ]);


        $status= response()->json($pages, 200);
        if($status)							
        {
            //add page properties
            
            $propertyArray = $request->get('properties');
            if(is_array($propertyArray)){
                foreach($propertyArray as $key=>$property){
                    
                    if(array_key_exists('propertyId',$property)){
                        $pageProperty = PageProperty::find($property['propertyId']);
                        if($pageProperty instanceof PageProperty){
                            
                            $pagePropertyValues = new PagePropertyValues();
                            $pagePropertyValues->pages_id=$pages->id;
                            $pagePropertyValues->page_property_id=$pageProperty->id;
                            $pagePropertyValues->propertyValue="";
                            if(array_key_exists('propertyValue',$property) || $files[$key]!=null){
                                if($pageProperty->propertyType == "file"){
                                    $image=$files[$key]['propertyValue'];
                                    $getimageName = uniqid().time().'.'.$image->getClientOriginalExtension();
                                    $image->move(public_path('uploads'), $getimageName);
                                    $pagePropertyValues->propertyValue=$getimageName;
                                }else{
                                    $pagePropertyValues->propertyValue=$property['propertyValue'];
                                }
                            }
                            $pagePropertyValues->save();
                        }
                    }
                }
            }

            $data = array('success' =>true, 'message' => 'Success! Page property created successfully.');
            echo json_encode($data);
        }
        else
        {
            $data = array('success' =>false, 'message' => 'Failed! Something went wrong. Please try again.');
            echo json_encode($data);
        }
    }


    public function update(Request $request)
    { 
      
        $validatedData = Validator::make($request->all(),[
            'title' => 'required|max:191',
            'mainId' => 'required|max:191',
        ]);
        if ($validatedData->fails()) {
            return response()->json($validatedData->errors());
        }
        $pages = Pages::find($request->get('mainId'));
        if($pages->title != $request->get('title')){
            $validatedData = Validator::make($request->all(),[
                'title' => 'unique:pages'
            ]);
            if ($validatedData->fails()) {
                return response()->json($validatedData->errors());
            }
        }
        $files= $request->file('properties');
        if(empty($files)){
            $files=[];
        }
        $pages->title=$request->get('title');
        $slug=preg_replace('/[^A-Za-z0-9-]+/', '-', strtolower($pages->title));
        $pages->slug=$slug;
        $pages->save();
        $status= response()->json($pages, 200);
        if($status)							
        {
            //add page properties
            
            $propertyArray = $request->get('properties');
            if(is_array($propertyArray)){

                // // code by nandeesh
                $existingPropertyArray = $pages->page_property_values()->get();
                $existId=[];
                
                
                foreach($propertyArray as $key=>$property){
                    if(array_key_exists('mainId',$property)){
                        $existId[]=$property['mainId'];
                    }
                }

                foreach($existingPropertyArray as $prop){
                    if(!(in_array($prop->id, $existId))){
                        $prop->delete();
                    }
                }
                // //end of code

                foreach($propertyArray as $key=>$property){
                    
                    if(array_key_exists('propertyId',$property)){
                        $pageProperty = PageProperty::find($property['propertyId']);
                        if($pageProperty instanceof PageProperty){
                            $flagTest=true;
                            if(array_key_exists('mainId',$property)){
                                $testPropertyValues = PagePropertyValues::find($property['mainId']);
                                if($testPropertyValues instanceof PagePropertyValues){
                                    $pagePropertyValues = $testPropertyValues;
                                    $flagTest=false;
                                }
                            }
                            if($flagTest){
                                $pagePropertyValues = new PagePropertyValues();
                                $pagePropertyValues->pages_id=$pages->id;
                                $pagePropertyValues->page_property_id=$pageProperty->id;
                            }


                            if(array_key_exists('propertyValue',$property) || array_key_exists($key,$files)){
                                if($pageProperty->propertyType == "file"){
                                    $getimageName="";
                                    $image=$files[$key]['propertyValue'];
                                    $getimageName = uniqid().time().'.'.$image->getClientOriginalExtension();
                                    $image->move(public_path('uploads'), $getimageName);
                                    $pagePropertyValues->propertyValue=$getimageName;
                                }else{
                                    $pagePropertyValues->propertyValue=$property['propertyValue'];
                                }
                            }else{
                                $pagePropertyValues->propertyValue="";
                            }
                            $pagePropertyValues->save();
                        }
                    }
                }
            }

            $data = array('success' =>true, 'message' => 'Success! Page property updated successfully.');
            echo json_encode($data);
        }
        else
        {
            $data = array('success' =>false, 'message' => 'Failed! Something went wrong. Please try again.');
            echo json_encode($data);
        }       
        
    }
    public function index()
    {
      $pages= Pages::all()->toArray(); 
       return response()->json($pages, 200);
    } 


    public function pageSlug()
    {
      $menu= Pages::select('title','slug')->get()->toArray(); 
      return response()->json($menu, 200);
    }

    public function destroy($id)
    {

    }

    public function show($id)
    {
        return response("hello");
    }
}
