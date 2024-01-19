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
use App\Models\ModuleManagerMeta;
use DB;



class SocialHistoryController extends Controller
{
    public function Fields($role_id=0)
    {
            $keyboard_types =  [
                                    'default',
                                    'number-pad',
                                    'decimal-pad',
                                    'numeric',
                                    'email-address',
                                    'phone-pad',
                                    'url'
                                ];

            $filed_types =  [
                                'text',
                                'image',
                                'number',
                                'dropdown',
                                'datepicker',
                                'radio',
                                'checkbox',
                                'textarea'
                            ]; 

            // fields start
                $fields = [
                        [
                            '0',    
                            'Marital Status',
                            'marital_status',
                            $filed_types[3],
                            $keyboard_types[0],
                            ['Married','Single','Divorced','Widowed'],
                            '',
                            false
                        ],
                        [
                            '1',    
                            'Family Status',
                            'family_status',
                            $filed_types[3],
                            $keyboard_types[0],
                            ['Married','Single','Divorced','Separated','Widowed','Cohabiting','Single Parent','Blended Family','Extended Family','Childless','Empty Nesters','Foster Family','Adoptive Family'],
                            '',
                            false
                            
                        ],
                        [
                            '2',    
                            'Living Situation',
                            'living_situation',
                            $filed_types[3],
                            $keyboard_types[0],
                            ['Shared Housing','Single-Family Home','Apartment','Condominium','Townhouse','Townhouse','Duplex'],
                            '',
                            false
                        ],
                        [
                            '3',    
                            'Do you live alone or with others? *',
                            'live_alone_or_with_others',
                            $filed_types[3],
                            $keyboard_types[0],
                            ['Alone ', 'Others'],
                            '',
                            false
                        ],
                        [
                            '4',    
                            'What type of activities do you participate in and how frequently?',
                            'type_of_activities_frequently',
                            $filed_types[7],  
                            $keyboard_types[0],
                            [],
                            '',
                            false
                        ]                                          
                ];

            return $fields;
    }

    public function GetFields(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'role_id' => 'required'
        ]);
        
        if ($validator->fails()) {

            $errors = $validator->errors();

            return response()->json([
                'message' => 'Validation Failed',
                'data' => $errors
            ],400);


        } else {

            $validatedData =  $validator->valid();

            // fields
            $fields = $this->Fields();

                    $social_history =  DB::table('social_history')->where('user_id',$validatedData['user_id'])->get();

                    $social_history = json_decode(json_encode($social_history), true);

                    for($i=0; $i < count($fields) ;$i++)
                    {
                        for( $j=0; $j < count($social_history) ; $j++ ) 
                        {

                            if( $fields[$i][2] == $social_history[$j]['option'] )
                            {
                                $fields[$i][6] = $social_history[$j]['value'];
                            }
                        }
                    }

                     

                     $data['fields'] = $fields;
                     $data['base_url_for_images'] = url('/images').'/';
                     
                     
                     return response()->json([
                         'message' => 'Get fields successfully',
                         'data' => $data
                     ],200);
             
                }
            
    }

    public function StoreOrUpdateFields(Request $request)
    { 

        try
        {
                // fields
                $fields = $this->Fields();
                $validator_array = ['user_id' =>'required','role_id'=>'required'];
                $request_only = ['user_id','role_id'];
                $i = 2;

                foreach($fields as $field)
                {

                    if($field[3] == "image")
                    {
                        $file_validation = '|max:524288';
                    }
                    else
                    {
                        $file_validation = '';
                    }

                    $validator_array[$field[2]] = ($field[7] == true) ? 'required'.$file_validation : 'nullable'.$file_validation;
                    $request_only[$i-2] = $field[2];
                    $i++;
                }

                $validator = Validator::make($request->all(),$validator_array);

        if ($validator->fails()) {


            $errors = $validator->errors();

            return response()->json([
                'message' => 'Validation Failed',
                'data' => $errors
            ],422);


        } else {
        
        $validatedData =  $validator->valid();

        $meta = $request->only($request_only);


        $msg = 'Saved';
    

        if(DB::table('social_history')->where('user_id', $validatedData['user_id'])->where('role_id', $validatedData['role_id'])->exists())
        {
            DB::table('social_history')->where('user_id', $validatedData['user_id'])->where('role_id', $validatedData['role_id'])->delete();
            $msg = 'Updated';
        }


            $MetaFields = [];
            foreach ($meta as $option => $value) {

                if ($request->hasFile($option))
                {
                    $file = $request->file($option);
                    $fileName = time() . $file->getClientOriginalName() . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path('images'), $fileName);
                    $value = $fileName;
                }

                $MetaFields[] = 
                [
                    'user_id' => $validatedData['user_id'],
                    'role_id' => $validatedData['role_id'],
                    'option' => $option,
                    'value' => $value,
                    'created_at' => now()
                ];
            }
    
            DB::table('social_history')->insert($MetaFields);

            return response()->json([
                'message' => 'Data '.$msg.' Successfully'
            ],200);
        }

        }        
        catch(Exception $e) 
        {
            return response()->json([
                'message' => $e->getMessage()
            ],500);
        }

    }

}