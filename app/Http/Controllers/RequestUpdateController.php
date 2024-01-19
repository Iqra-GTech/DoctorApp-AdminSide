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
use App\Models\RequestUpdate;
use DB;



class RequestUpdateController extends Controller
{
    public function index(Request $request)
    {

        $request_updates_quary = RequestUpdate::query();

       if($request->has('user_id'))
       {
            $request_updates_quary->where("user_id", $request->user_id);
            $recordsFiltered = $recordsTotal = RequestUpdate::where("user_id", $request->user_id)->count();
       }
       else
       {
            $recordsFiltered = $recordsTotal = RequestUpdate::count();
       } 

       if($request->filled('filter'))
       {
           [$request_updates_quary , $recordsFiltered] =  $this->filter($request->filter,$request_updates_quary,$recordsFiltered);
       }


       $start = $request->input('start', 0);
       $length = $request->input('length', 10);
       $request_updates_quary->offset($start)->limit($length);
       $request_updates_quary->orderBy('id', 'desc');
       $request_updates = $request_updates_quary->get();
     
       $request_updates_data = [];
       
      foreach($request_updates as $request_update)
      {
        $request_updates_data[] =  [				
            'id' => $request_update->id,
            'title' => $request_update->title,
            'discription' => $request_update->discription,
            'resolved' => $request_update->resolved, // Resolved , Pending
            'date' => $request_update->date,
            'user' => $request_update->user,
            'module' => $request_update->module,
            ];
      }

      $data['request_updates'] = $request_updates_data;


        return response()->json([
            'message' => 'Get request updates list successfully',
            'data' => $data,
            'recordsFiltered' => $recordsFiltered,
            'recordsTotal' => $recordsTotal,
            'draw' => intval($request->draw),
            'test' => $request->filter
        ],200);
    }

    public function show($id)
    {
        $request_updates =  RequestUpdate::where("id", $id)->first();
 
       $data['discription'] = $request_updates->discription;
 
 
         return response()->json([
             'message' => 'Get request updates discription successfully',
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

           $request_update =   RequestUpdate::where('id', $id)->first();

           $this->save_notifications('Request To Update '.$validatedData['resolved'],'The Request Has Been '.$validatedData['resolved'].' Successfully','0',$request_update->user_id,'request_for_update_'.strtolower($validatedData['resolved']));

           RequestUpdate::where('id', $id)->update([
                'resolved' => $validatedData['resolved'],
                'updated_by' => Auth::id()
            ]);
 
            return response()->json([
                'message' => 'Status changed successfully'
            ],200);
            
        }        

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

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'discription' => 'required',
            'user_id' => 'required',
            'module_id' => 'required',
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

           $this->save_notifications('Request For Update','The Request Send Successfully',$validatedData['user_id'],'0','request_for_update');

           RequestUpdate::insert($validatedData);
    
            return response()->json([
                'message' => 'A request for update has been send successfully'
            ],200);
            
        }        

    }

}
