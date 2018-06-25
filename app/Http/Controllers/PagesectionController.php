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
            'page_section_id' => 'required|exists:page_sections,id',
            'page_section_title' => 'required',
            'properties' => 'array',
            'properties.*.id' => 'required|exists:page_section_props,id',
            'properties.*.value' => 'required'
        );   
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json($validator->errors(),400);
        }
        $section=PageSection::find($request->page_section_id);
        $section->title=$request->page_section_title;
        $section->page_section_props()->saveMany(array_map(function($prop){
            if($prop['id'] != null)
            {
                $sectionProp= PageSectionProp::find($prop['id']);
                if($sectionProp->type == 'file'):
                    $file=$prop['image_file'];
                    $image = uniqid().'sect.'.$file->getClientOriginalExtension();
                    $destinationPath = public_path('/uploads');
                    $file->move($destinationPath, $image);
                    $sectionProp->link = "/uploads/".$image;
                else:
                    if(array_key_exists('link', $prop))
                        $sectionProp->link = $prop['link'];
                endif;
                $sectionProp->type = $prop['value'];
            }
            return $sectionProp;
        }, $request->properties));

        $section->save();

        return response("Section updated successfully",200);
    }

    public function show($id){
        $sections = PageSection::with('page_section_props.section_properties')->where('id','=',$id)->get()->toArray();
        return $sections[0];
    }

}