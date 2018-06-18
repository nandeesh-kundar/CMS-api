<?php

namespace App\Http\Controllers;
use Validator;
use App\PageSection;
use App\PageSectionProp;

use App\PagePropertyValues;
use Illuminate\Http\Request;

class PagesectionController extends Controller
{

    public function store(Request $request){
        $rules = array(
            'page_section_id' => 'required:exists:page_sections,id',
            'properties' => 'required',
            'properties.*.id' => 'required',
            'properties.*.value' => 'required'
        );   
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json($validator->errors(),400);
        }
        $section=PageSection::find($request->page_section_id);
        $section->page_section_props()->saveMany(array_map(function($prop){
            if($prop['id'] != null)
            {
                $sectionProp= PageSectionProp::find($prop['id']);
                $sectionProp->value = $prop['value'];
                $sectionProp->link = $request->link;
            }
            return $sectionProp;
        }, $inputs['properties']));

        $section->save();

        return response("Section updated successfully",200);
    }

}