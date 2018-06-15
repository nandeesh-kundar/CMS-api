<?php

namespace App\Http\Controllers;

use Validator;
use App\Section;
use App\SectionProperties;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class SectionController extends Controller
{
    public function updateSection(Request $request){
        $rules = array(
            'sections.*.title' => 'required|unique:sections,id,sections.*.id',
            'sections.*.properties.*.key' => 'required',
            'sections.*.properties.*.type' => 'required|in:string,text,number,file',
        );
        $validator = Validator::make($request->all(), $rules)->validate();

        $sections=$request->all();$i=0;
        foreach($sections['sections'] as $section){
            if($section["id"] == null){
                $sect=new Section();
                $sect->title=$section['title'];
                $sect->save();
                $sect->section_properties()->saveMany(array_map(function ($property) {
                    return new SectionProperties($property);
                }, $section['properties']));
            }else{
                $sect = Section::find($section["id"]);
                $sect->title=$section["title"];
                $sect->save();
                $sect->section_properties()->saveMany(array_map(function($prop) {
                    if($prop["id"] == null){
                        return new SectionProperties($prop);
                    }else{
                        $sprop = SectionProperties::find($prop["id"]);
                        $sprop->key=$prop['key'];
                        $sprop->type=$prop['type'];
                        return $sprop;
                    }
                },$section['properties']));
            }
        }
        return response()->json("Successfully updated.", 200);
    }

    public function indexSection()
    {
        $sections= Section::with('section_properties')->get()->toArray(); 
        return response()->json($sections, 200);
    } 

    public function sectionDelete($id){
        $sect = Section::find($id);
        try{
            $sect->delete();
            return response()->json("Successfully deleted.", 200);
        }catch(\Exception $e){
            return response()->json($e->getMessage(), 404);
        }
    }

    public function sectionPropertyDelete($id){
        $sect = SectionProperties::find($id);
        try{
            $sect->delete();
            return response()->json("Successfully deleted.", 200);
        }catch(\Exception $e){
            return response()->json($e->getMessage(), 404);
        }
    }
}