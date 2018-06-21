<?php

namespace App\Http\Controllers;

use App\Pages;
use Validator;
use App\PageSection;
use App\PageProperty;
use App\PageSectionProp;
use App\SectionProperties;
use App\PagePropertyValues;
use Illuminate\Http\Request;

class PagesController extends Controller
{
    public function store(Request $request){
        $rules = array(
            'title' => 'required',
            'sections' =>'array',
            'sections.*.section_id' => 'required|exists:sections,id',
            'sections.*.title' => 'required'
        );
        $pages=$request->all(); 
        $flag=false; 
        if($request->id != null){
            $pageobj=Pages::find($request->id);
            if($request->title != $pageobj->title)
                $flag=true;
        }else{
            $pageobj=new Pages();
            $flag=true;
        }
        if($flag){
            $rules['title']='required|unique:pages';
        }
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json($validator->errors(),400);
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
        if($request->id == null){
            $pageobj->title=$pages['title']; 
            $pageobj->slug=$slug;
            $pageobj->description=$request->description;
            $pageobj->save();
            if(array_key_exists('sections',$pages)):
                foreach($pages['sections'] as $sectObj){
                    if($sectObj['id']==null){
                        $sect=new PageSection();
                        $sect->pages_id=$pageobj->id;
                        $sect->section_id=$sectObj['section_id'];
                        $sect->title=$sectObj['title'];
                        $sect->save();
                        $props=SectionProperties::where('section_id','=', $sect['section_id'])->get();
                        foreach($props as $prop){
                            $sectProp = new PageSectionProp();
                            $sectProp->ps_id = $sect->id;
                            $sectProp->prop_id = $prop->id;
                            $sectProp->type = $prop->type;
                            $sectProp->save();
                        }
                    }else{
                        $sect=PageSection::find($sectObj["id"]);
                        $sect->title=$sectObj['title'];
                        $sect->save();
                    }
                }
            endif;
        }else{
            $pageobj=Pages::find($request->id);
            $pageobj->title=$pages['title']; 
            $pageobj->slug=$slug;
            $pageobj->save();
            if(array_key_exists('sections',$pages)):
                foreach($pages['sections'] as $sectObj){
                    if($sectObj['id']==null){
                        $sect=new PageSection();
                        $sect->pages_id=$pageobj->id;
                        $sect->section_id=$sectObj['section_id'];
                        $sect->title=$sectObj['title'];
                        $sect->save();
                        $props=SectionProperties::where('section_id','=', $sect['section_id'])->get();
                        foreach($props as $prop){
                            $sectProp = new PageSectionProp();
                            $sectProp->ps_id = $sect->id;
                            $sectProp->prop_id = $prop->id;
                            $sectProp->type = $prop->type;
                            $sectProp->save();
                        }
                    }else{
                        $sect=PageSection::find($sectObj["id"]);
                        $sect->title=$sectObj['title'];
                        $sect->save();
                    }
                }
            endif;
        }
        return response("Page updated successfully",200);
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

    public function sectionDestroy($id)
    {
        $pageobj=PageSection::find($id);    
        $pageobj->delete();
        return response()->json("Successfully deleted", 200);
    }

    public function destroy($id)
    {
        $pageobj=Pages::find($id);    
        $pageobj->delete();
        return response()->json("Successfully deleted", 200);
    }

    public function show($id)
    {
        return Pages::with('page_sections')->where('id','=',$id)->get()->toArray(); 
    }
}
