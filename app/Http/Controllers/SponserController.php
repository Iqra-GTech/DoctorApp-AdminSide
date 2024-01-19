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
use App\Models\Sponser;
use App\Models\ModuleManagerMeta;
use DB;



class SponserController extends Controller
{

    public function index(Request $request)
    {

    $sponsers_quary = Sponser::query();

    $recordsFiltered = $recordsTotal = Sponser::count();

    if($request->filled('filter'))
    {
        [$sponsers_quary , $recordsFiltered] =  $this->filter($request->filter,$sponsers_quary,$recordsFiltered);
    }


    $start = $request->input('start', 0);
    $length = $request->input('length', 10);
    $sponsers_quary->offset($start)->limit($length);
    $sponsers_quary->orderBy('id', 'desc');
    $sponsers = $sponsers_quary->get();
     
       $sponsers_data = [];
       
      foreach($sponsers as $sponser)
      {
        $sponsers_data[] =  [				
            'id' => $sponser->id,
            'name' => $sponser->name,
            'logo' => url('/images/'.$sponser->logo),
            'priority' => $sponser->priority,
            'link' => $sponser->link
            ];
      }

      $data['sponsers'] = $sponsers_data;


        return response()->json([
            'message' => 'Get sponsers list successfully',
            'data' => $data,
            'recordsFiltered' => $recordsFiltered,
            'recordsTotal' => $recordsTotal,
            'draw' => intval($request->draw)
        ],200);
    }


    public function filter($filter,$quary,$recordsFiltered)
    {
        foreach ($filter as $f) 
        {
            if($f['value'] != '' && $f['value'] != null)
            {
              $quary->where($f['key'], 'like', '%' . $f['value'] . '%');
              $recordsFiltered =   $quary->count();
            }
        }
        return [$quary,$recordsFiltered];
    }

    public function show($id)
    {

       $sponser =  Sponser::where('id',$id)->orderBy('priority')->first();
     
   
       
        $sponsers_data=  [				
            'id' => $sponser->id,
            'name' => $sponser->name,
            'logo' => url('/images/'.$sponser->logo),
            'priority' => $sponser->priority,
            'link' => $sponser->link
        ];
      

      $data['sponser'] = $sponsers_data;


        return response()->json([
            'message' => 'Get sponser successfully',
            'data' => $data
        ],200);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'priority' => 'required',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'link'=> 'required',
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
            }
            else
            {
                unset($validatedData['logo']);
            }
           
            
                unset($validatedData['_method']);
                unset($validatedData['edit_id']);
           
            
           Sponser::where('id', $id)->update($validatedData);
    
            return response()->json([
                'message' => 'Sponser stored successfully'
            ],200);
            
        }        

    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'priority' => 'required',
            'logo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'link' => 'required',
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
            }
           
           

           Sponser::insert($validatedData);
    
            return response()->json([
                'message' => 'Sponser stored successfully'
            ],200);
            
        }        

    }


    public function destroy(Sponser $sponser){

        $sponser->delete();

        return response()->json([
            'message' => 'Sponser deleted successfully'
        ],200);

    }




}
