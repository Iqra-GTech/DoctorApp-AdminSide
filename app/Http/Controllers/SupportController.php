<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Auth;
use App\Models\ModuleManager;
use App\Models\User;
use App\Models\UserMeta;
use App\Models\Role;
use App\Models\Support;
use App\Models\ModuleManagerMeta;
use DB;



class SupportController extends Controller
{

    public function index(Request $request)
    {
        $support_quary = Support::query();

       if($request->has('user_id'))
       {
          $support_quary->where("user_id", $request->user_id);
          $recordsFiltered = $recordsTotal = Support::where("user_id", $request->user_id)->count();
       }
       else
       {
          $recordsFiltered = $recordsTotal = Support::count();
       }
       
       if($request->filled('filter'))
       {
           [$support_quary , $recordsFiltered] =  $this->filter($request->filter,$support_quary,$recordsFiltered);
       }


       $start = $request->input('start', 0);
       $length = $request->input('length', 10);
       $support_quary->offset($start)->limit($length);
       $support_quary->orderBy('id', 'desc');
       $supports = $support_quary->get();
     
       $supports_data = [];
       
      foreach($supports as $support)
      {
        $supports_data[] =  [				
            'id' => $support->id,
            'title' => $support->title, 
            'discription' => $support->discription,
            'resolved' => $support->resolved, // Approve , Pending //  Unapproved
            'date' => $support->date,
            'user' => $support->user,
            ];
      }

      $data['supports'] = $supports_data;


        return response()->json([
            'message' => 'Get supports list successfully',
            'data' => $data,
            'recordsFiltered' => $recordsFiltered,
            'recordsTotal' => $recordsTotal,
            'draw' => intval($request->draw)
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
    
    public function show($id)
    {
        $support =  Support::where("id", $id)->first();
 
       $data['discription'] = $support->discription;
       $data['images'] = [];
       foreach(explode(',',$support->images) as $images)
       {
        $data['images'][] =  url('images/'.$images);
       }
 
 
         return response()->json([
             'message' => 'Get support details successfully',
             'data' => $data
         ],200);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'resolved' => 'required',
        ]);
        
        if ($validator->fails()) {

            $errors = $validator->errors();

            return response()->json([
                'message' => 'Validation Failed',
                'data' => $errors
            ],422);


        } else {
          
           $validatedData =  $validator->valid();

           Support::where('id', $id)->update([
                'resolved' => $validatedData['resolved'],
                'updated_by' => Auth::id()
            ]);

          $Support_Data =   Support::where('id', $id)->first();

                if($validatedData['resolved'] == "Resolved"){

                    $this->save_notifications('Request To Support Resolved','The Request Has Been Resolved Successfully','0',$Support_Data->user_id,'support_resolved');

                }

            


            return response()->json([
                'message' => 'Status changed successfully'
            ],200);
            
        }        

    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'discription' => 'required',
            'user_id' => 'required',
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);
        
        if ($validator->fails()) {

            $errors = $validator->errors();

            return response()->json([
                'message' => 'Validation Failed',
                'data' => $errors
            ],422);


        } else {
          
           $validatedData =  $validator->valid();

           $validatedData['resolved'] = 'Pending';

           $images = $request->file('images');
           $validatedData['images'] = '';
            $file_no = 0;
           

           foreach($images as $image)
            {
                $imageName = time() . $file_no . '.' .$image->getClientOriginalExtension();
                $image->move(public_path('images'), $imageName);
                $validatedData['images'] .=  $imageName.',';
                $file_no++;
            }
           
            $validatedData['images'] = rtrim($validatedData['images'], ',');

           Support::insert($validatedData);

           $this->save_notifications('Request To Support','The Request Send Successfully',$validatedData['user_id'],'0','support_store');

    
            return response()->json([
                'message' => 'Support Stored successfully'
            ],200);
            
        }        

    }
    
}
