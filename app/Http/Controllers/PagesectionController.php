<?php

namespace App\Http\Controllers;
use Validator;
use App\PageSection;
use App\PageProperty;
use App\PagePropertyValues;

use Illuminate\Http\Request;

class PagesectionController extends Controller
{

    public function store(Request $request){
        $rules = array(
            'page_section_id' => 'required:exists:page_sections,id',
            'properties' => 'required',
            'properties.*.value' => 'required',
            'properties.*.prop_id' => 'required:exists:section_properties,id',
        );   
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json($validator->errors(),400);
        }
        $inputs=$request->all(); 
        $section=PageSection::find($request->page_section_id);
        $section->page_section_props()->saveMany(array_map(function($prop){
            if($prop['id'] == null)
            {
                $sectionProp= new PageSectionProp();
                $sectionProp->prop_id = $prop['prop_id'];
                $sectionProp->value = $prop['value'];
                $sectionProp->link = $request->link;
            }else{
                $sectionProp= PageSectionProp::find($prop['id']);
                $sectionProp->prop_id = $prop['prop_id'];
                $sectionProp->value = $prop['value'];
                $sectionProp->link = $request->link;
            }
            return $sectionProp;
        }, $inputs['properties']));

        $section->save();

        return response("Section updated successfully",200);
    }

}