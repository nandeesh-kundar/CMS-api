<?php

namespace App\Http\Controllers;

use App\Pages;
use Validator;
use App\PageSection;
use App\PageProperty;
use App\PagePropertyValues;
use Illuminate\Http\Request;

class PagesController extends Controller
{
    public function store(Request $request){
        $rules = array(
            'title' => 'required',
            'description' => 'required',
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
            $pageobj->save();
            $pageobj->page_sections()->saveMany(array_map(function($page){
                $sect=new PageSection();
                $sect->section_id=$page['section_id'];
                $sect->title=$page['title'];
                return $sect;
            },$pages['sections']));
        }else{
            $pageobj=Pages::find($request->id);
            $pageobj->title=$pages['title']; 
            $pageobj->slug=$slug;
            $pageobj->save();
            $pageobj->page_sections()->saveMany(array_map(function($page){
                if($page['id']==null){
                    $sect=new PageSection();
                    $sect->section_id=$page['section_id'];
                    $sect->title=$page['title'];
                }else{
                    $sect=PageSection::find($page["id"]);
                    $sect->section_id=$page['section_id'];
                    $sect->title=$page['title'];
                }
                return $sect;
            },$pages['sections']));
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
        return response("hello");
    }
}
