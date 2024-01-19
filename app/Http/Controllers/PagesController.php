<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Auth;
use App\Models\ModuleManager;
use App\Models\User;
use App\Models\UserMeta;
use App\Models\Role;
use App\Models\Page;
use App\Models\ModuleManagerMeta;
use DB;



class PagesController extends Controller
{

    public function index(Request $request)
    {
        $page_quary = Page::query();
        

       if($request->has('role_id'))
       {
          $page_quary->where("role_id", $request->role_id);
          $page_quary->OrWhere("role_id", 0);
          $page_quary->where("del", 0);
          $recordsFiltered = $recordsTotal = Page::where("del", 0)->where("role_id", $request->role_id)->count();
       }
       else
       {
          $page_quary->where("del", 0);
          $recordsFiltered = $recordsTotal = Page::where("del", 0)->count();
       }
       
       if($request->filled('filter'))
       {
           [$page_quary , $recordsFiltered] =  $this->filter($request->filter,$page_quary,$recordsFiltered);
       }


       $start = $request->input('start', 0);
       $length = $request->input('length', 10);
       $page_quary->offset($start)->limit($length);
       $page_quary->orderBy('id', 'desc');
       $pages = $page_quary->get();
     
       $pages_data = [];
       
      foreach($pages as $page)
      {
        $pages_data[] =  [				
            'id' => $page->id,
            'name' => $page->name,
            'status' => $page->status,
            'logo' => url('/').'/images/'.$page->logo
            ];
      }

      $data['pages'] = $pages_data;


        return response()->json([
            'message' => 'Get pages list successfully',
            'data' => $data,
            'recordsFiltered' => $recordsFiltered,
            'recordsTotal' => $recordsTotal,
            'draw' => intval($request->draw)
        ],200);
    }

    public function show($id)
    {
        
        $page = Page::findOrFail($id);
        $data['page']  = [
             'id' => $page->id,
        'name' => $page->name,
        'status' => $page->status,
        'role_id' => $page->role_id,
        'description' => $page->description,
        'logo' => url('/').'/images/'.$page->logo,


        ];
        return response()->json([
            'message' => 'Get page successfully',
            'data' => $data
        ],200);

    }

    public function filter($filter,$quary,$recordsFiltered)
    {
         $arr = [];
        foreach ($filter as $f) 
        {
            if($f['value'] != '' && $f['value'] != null)
            {
                if(str_contains($f['key'],'.'))
                {
                    //user_id.users.email
                    $arr  = explode(".",$f['key']);
                    $ids =  DB::table($arr[1])->where($arr[2], 'like', '%' . $f['value'] . '%')->select('id')->get();
                    $ids_list = [];
                     foreach($ids as $id)
                     {
                        $ids_list[] = $id->id;
                     }

                     $quary->whereIn($arr[0],$ids_list);
                     $recordsFiltered =   $quary->count();
                    
                }
                else
                {
                    $quary->where($f['key'], 'like', '%' . $f['value'] . '%');
                    $recordsFiltered =   $quary->count();
                }
            }
        }
        return [$quary,$recordsFiltered];
    }

    public function destroy(Page $page){

        Page::where('id', $page->id)->delete();

        return response()->json([
            'message' => 'Page deleted successfully'
        ],200);


    }

    public function updateStatus(Request $request, Page $page)
    {
       
        $validator = Validator::make($request->all(), [
            'status' => 'required'
        ]);
        
        if ($validator->fails()) {

            $errors = $validator->errors();

            return response()->json([
                'message' => 'Validation Failed',
                'data' => $errors
            ],400);


        } else {
          
            $validatedData =  $validator->valid();
            $page->status = $validatedData['status'];
            $page->save();

            return response()->json([
                'message' => 'Page status updated successfully'
            ],200);
            
        }        
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
            'role' => 'required',
            'logo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);
        
        if ($validator->fails()) {

            $errors = $validator->errors();

            return response()->json([
                'message' => 'Validation Failed',
                'data' => $errors
            ],422);



        } else {
          
           $validatedData =  $validator->valid();
           
           $image = $request->file('logo');
           $validatedData['logo'] = '';
           
            if($request->hasFile('logo')){

                $imageName = time() . '.' .$image->getClientOriginalExtension();
                $image->move(public_path('images'), $imageName);
                $validatedData['logo'] =  $imageName;

                Page::insert([
                    'name' =>$validatedData['name'],
                    'role_id' => $validatedData['role'],
                    'description' => $validatedData['description'],
                    'logo' => $validatedData['logo']
                   ]);
            }
            else
            {
                Page::insert([
                    'name' =>$validatedData['name'],
                    'role_id' => $validatedData['role'],
                    'description' => $validatedData['description']
                   ]);
            }

            return response()->json([
                'message' => 'Page created successful'
            ],200);
            
        }        

    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
            'role' => 'required',
            'id' => 'required',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);
        
        if ($validator->fails()) {

            $errors = $validator->errors();

            return response()->json([
                'message' => 'Validation Failed',
                'data' => $errors
            ],422);


        } else {
          
           $validatedData =  $validator->valid();          

           $image = $request->file('logo');
           $validatedData['logo'] = '';
           
            if($request->hasFile('logo')){

                $imageName = time() . '.' .$image->getClientOriginalExtension();
                $image->move(public_path('images'), $imageName);
                $validatedData['logo'] =  $imageName;

                Page::where('id',$validatedData['id'])->update([
                    'name' =>$validatedData['name'],
                    'role_id' => $validatedData['role'],
                    'description' => $validatedData['description'],
                    'logo' => $validatedData['logo']
                   ]);
            }
            else
            {
                Page::where('id',$validatedData['id'])->update([
                    'name' =>$validatedData['name'],
                    'role_id' => $validatedData['role'],
                    'description' => $validatedData['description']
                   ]);
            }
    
         
            return response()->json([
                'message' => 'Page updated successful'
            ],200);
            
        }        

    }




}
