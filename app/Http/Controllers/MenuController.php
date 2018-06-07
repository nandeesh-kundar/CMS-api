<?php

namespace App\Http\Controllers;

use App\Menu;
use Validator;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = Validator::make($request->all(),[
            'title' => 'required|max:191',
            'linkType' => 'required|in:custom,page',
            'menuType' => 'required|in:primary,secondary,sidebar1,sidebar2,footer1,footer2,footer3,footer4,social'
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
        if($request->parent_id != null){
            $validatedData = Validator::make($request->all(),[
                'parent_id' => 'exists:menus,id',
            ]);
            if ($validatedData->fails()) {
                return response()->json($validatedData->errors());
            }
            $parentMenu = Menu::find($request->parent_id);
            $menus = $parentMenu->children()->create(
              ['title'=> $request->title,
               'linkType'=> $request->linkType,
               'menuType'=> $request->menuType,
               'customLink'=> $request->customLink,
               'pageSlug'=> $request->pageSlug
            ]);
        }else{
            $menus = Menu::create(
              ['title'=> $request->title,
               'linkType'=> $request->linkType,
               'menuType'=> $request->menuType,
               'customLink'=> $request->customLink,
               'pageSlug'=> $request->pageSlug,
               'parent_id'=> $request->parent_id,
            ]);
        }
        $status= response()->json($menus, 200);
        if($status)							
        {
            $menu= Menu::with('children', 'parent')->where('id',$menus->id)->get()->toArray(); 
            $data = array('success' =>true, 'message' => 'Success! Page property created successfully.', 'data'=>$menu[0]);
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
        $menus = Menu::find($id);
        $validatedData = Validator::make($request->all(),[
            'title' => 'required|max:191',
            'linkType' => 'required|in:custom,page',
            'menuType' => 'required|in:primary,secondary,sidebar1,sidebar2,footer1,footer2,footer3,footer4,social',
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
        if($request->parent_id != null){
            $validatedData = Validator::make($request->all(),[
                'parent_id' => 'exists:menus,id',
            ]);
            if ($validatedData->fails()) {
                return response()->json($validatedData->errors());
            }
            $menus->title = $request->title;
            $menus->linkType = $request->linkType;
            $menus->menuType = $request->menuType;
            $menus->customLink = $request->customLink;
            $menus->pageSlug = $request->pageSlug;
            $menus->parent_id = $request->parent_id;
        }else{
            $menus->title = $request->title;
            $menus->linkType = $request->linkType;
            $menus->menuType = $request->menuType;
            $menus->customLink = $request->customLink;
            $menus->pageSlug = $request->pageSlug;
            $menus->parent_id = $request->parent_id;
        }
        $menus->save();
        $status= response()->json($menus, 200);
        if($status)							
        {
            $menu= Menu::with('children', 'parent')->where('id',$menus->id)->get()->toArray(); 
            $data = array('success' =>true, 'message' => 'Success! Page property created successfully.','data'=>$menu[0]);
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
      $menu= Menu::with('children', 'parent')->get()->toArray(); 
      return response()->json($menu, 200);
    }


    public function destroy($id)
    {
      $menu= Menu::find($id);
      $menu->delete();
      return response()->json($menu->toArray(), 200);
    }
}
