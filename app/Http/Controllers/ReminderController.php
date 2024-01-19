<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Auth;
use App\Models\ModuleManager;
use App\Models\User;
use App\Models\UserMeta;
use App\Models\Role;
use App\Models\Reminder;
use App\Models\ModuleManagerMeta;
use DB;



class ReminderController extends Controller
{

    public function index(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'user_id' => 'required'
        ]);
        
        if ($validator->fails()) {

            $errors = $validator->errors();

            return response()->json([
                'message' => 'Validation Failed',
                'data' => $errors
            ],422);


        } else {
          
           $validatedData =  $validator->valid();

           $reminders =  Reminder::where("user_id", $request->user_id)->get();

                
       $reminders_data = [];
       
       foreach($reminders as $reminder)
       {
         $reminders_data[] =  [				
             'id' => $reminder->id,
             'title' => $reminder->title,
             'date' => $reminder->date,
             'time' => $reminder->time,
             'unique_id' => $reminder->unique_id,
             'status' => $reminder->status,
             'des' => $reminder->des,
             'recursion' => $reminder->recursion,
             'alert_before' => $reminder->alert_before,
             ];
       }
 
       $data['reminders'] = $reminders_data;
 
 
         return response()->json([
             'message' => 'Get reminders list successfully',
             'data' => $data
         ],200);

        }
    }

    public function create(Request $request)
    {
        echo "not needed any more";
        die;
      $validator = Validator::make($request->all(), [
            'user_id' => 'required'
        ]);
        
        if ($validator->fails()) {

            $errors = $validator->errors();

            return response()->json([
                'message' => 'Validation Failed',
                'data' => $errors
            ],422);


        } else {
          
            $validatedData =  $validator->valid();


            return response()->json([
                'message' => 'Reminder Set successfully',
                'data' => $data
            ],200);
        }

    }



    public function edit($id)
    {
        $reminder =  Reminder::find($id);

        $reminders_data =  [				
            'id' => $reminder->id,
            'title' => $reminder->title,
            'date' => $reminder->date,
            'time' => $reminder->time,
            'unique_id' => $reminder->unique_id,
            'status' => $reminder->status,
            'des' => $reminder->des,
            'recursion' => $reminder->recursion,
            'alert_before' => $reminder->alert_before,
        ];

        $request_to_doctor =  DB::table('request_to_doctor')
        ->join('users', 'users.id', '=', 'request_to_doctor.doctor_id')
        ->where(['request_to_doctor.patient_id'=> $reminder->user_id])
        ->distinct()
        ->select('users.id','users.email')
        ->get();



        $data['reminder'] = $reminders_data;


        return response()->json([
        'message' => 'Get reminder for update successfully',
        'data' => $data
        ],200);

    }
    

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'des' => 'nullable',
            'date' => 'required',
            'title' => 'required',
            'time' => 'required',
            'unique_id' => 'required',
            'status' => 'required',
            'recursion' => 'nullable',
            'alert_before' => 'nullable'

        ]);
        
        if ($validator->fails()) {

            $errors = $validator->errors();

            return response()->json([
                'message' => 'Validation Failed',
                'data' => $errors
            ],422);


        } else {
          
           $validatedData =  $validator->valid();

           Reminder::where('id', $id)->update($validatedData);            


            return response()->json([
                'message' => 'Reminder updated successfully'
            ],200);
            
        }        

    }

    public function store(Request $request)
    {
       
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'des' => 'nullable',
            'date' => 'required',
            'title' => 'required',
            'time' => 'required',
            'unique_id' => 'required',
            'status' => 'required',
            'recursion' => 'nullable',
            'alert_before' => 'nullable',
        ]);
        
        if ($validator->fails()) {

            $errors = $validator->errors();

            return response()->json([
                'message' => 'Validation Failed',
                'data' => $errors
            ],422);


        } else {
          
           $validatedData =  $validator->valid();

           $validatedData['date'] = date('d/m/Y', strtotime($validatedData['date']));


           Reminder::insert($validatedData);

    
            return response()->json([
                'message' => 'Reminder Set successfully'
            ],200);
            
        }        

    }


    public function destroy(Reminder $reminder)
    {

        Reminder::where('id', $reminder->id)->delete();

        return response()->json([
            'message' => 'Reminder deleted successfully'
        ],200);


    }


    public function updateStatus(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required'
        ]);
        
        if ($validator->fails()) {

            $errors = $validator->errors();

            return response()->json([
                'message' => 'Validation Failed',
                'data' => $errors
            ],422);


        } else {
          
           $validatedData =  $validator->valid();

           Reminder::where('id', $id)->update($validatedData);            


            return response()->json([
                'message' => 'Updated successfully'
            ],200);
            
        }        

    }




}
