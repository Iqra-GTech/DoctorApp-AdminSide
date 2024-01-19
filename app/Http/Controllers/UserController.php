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
use Carbon\Carbon; 


class UserController extends Controller
{

    public function index(Request $request)
    {
        $users_quary = User::query();
        $users_quary->where('del','0');

        $recordsFiltered = $recordsTotal = User::where('del','0')->count();


        if($request->filled('filter'))
        {
            [$users_quary , $recordsFiltered] =  $this->filter($request->filter,$users_quary,$recordsFiltered);
        }


        $start = $request->input('start', 0);
        $length = $request->input('length', 10);
        $users_quary->offset($start)->limit($length);
        $users_quary->orderBy('id', 'desc');
        $users = $users_quary->get();

        if($users)
        {
            $user_data = [];

            foreach($users as $user)
            {
                $has_profile = UserMeta::where(["user_id" => $user->id,"role_id" => $user->role_id])->count() ? true : false;

                $user_data[] = [
                    "id" => $user->id,
                    "email" => $user->email,
                    "phone_number" => $user->phone_number,
                    "active" => $user->active,
                    "created_by" => $user->created_by,
                    "created_at" => $user->created_at,
                    "role" => $user->role,
                    "verified" => $user->verified,
                    "has_profile" => $has_profile
                ];
    
            }

            $data =  [
                'user' => $user_data,
            ];

                return response()->json([
                    'message' => 'Get users list successfully',
                    'data' => $data,
                    'recordsFiltered' => $recordsFiltered,
                    'recordsTotal' => $recordsTotal,
                    'draw' => intval($request->draw)
                ],200);
 
        }
        else
        {
            return response()->json([
                'message' => 'Users not found'
            ],400);
        }
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

        $user = User::find($id);

        if($user)
        {
            $has_profile = UserMeta::where(["user_id" => $user->id,"role_id" => $user->role_id])->count() ? true : false;

            $user_data = [
                "id" => $user->id,
                "email" => $user->email,
                "phone_number" => $user->phone_number,
                "active" => $user->active,
                "created_by" => $user->created_by,
                "role" => $user->role,
                "verified" => $user->verified,
                "has_profile" => $has_profile
            ];

            $data =  [
                'user' => $user_data,
                ];

                return response()->json([
                    'message' => 'Get user successfully',
                    'data' => $data
                ],200);

        }
        else
        {
            return response()->json([
                'message' => 'User not found'
            ],400);
        }
        
    }

    public function edit($id)
    {
        $user = User::find($id);


        if($user)
        {
            $user_data = [
                "phone_number" => $user->phone_number
            ];

            $data =  [
                'user' => $user_data
                ];

                return response()->json([
                    'message' => 'Get user successfully',
                    'data' => $data
                ],200);

        }
        else
        {
            return response()->json([
                'message' => 'User not found'
            ],400);
        }
        
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'phone_number' => 'required'
        ]);
        
        if ($validator->fails()) {

            $errors = $validator->errors();

            return response()->json([
                'message' => 'Validation Failed',
                'data' => $errors
            ],400);


        } else {
          
           $validatedData =  $validator->valid();

           User::where('id', $id)->update([
                'phone_number' => $validatedData['phone_number']
            ]);          
            
            return response()->json([
                'message' => 'Updated successfully'
            ],200);
            
        }        

    }

    public function updateStatus(Request $request, User $user)
    {
       
        $validator = Validator::make($request->all(), [
            'active' => 'required'
        ]);
        
        if ($validator->fails()) {

            $errors = $validator->errors();

            return response()->json([
                'message' => 'Validation Failed',
                'data' => $errors
            ],400);


        } else {
          
            $validatedData =  $validator->valid();

           $user->active = $validatedData['active'];
           $user->save();

            return response()->json([
                'message' => 'User status updated successfully'
            ],200);
            
        }        
    }

    public function destroy(USER $user){

        User::where('id', $user->id)->update([
            'del' => '1'
        ]);

        return response()->json([
            'message' => 'User deleted successfully'
        ],200);


    }

    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'password' => 'required',
        ]);
        
        if ($validator->fails()) {

            $errors = $validator->errors();

            return response()->json([
                'message' => 'Validation Failed',
                'data' => $errors
            ],400);


        } else {

            $user = Auth::user();
            $oldPassword = $request->old_password;
            $newPassword = $request->password;
    
            if (Hash::check($oldPassword, $user->password)) {
                $user->password = Hash::make($newPassword);
                $user->save();
    
                return response()->json([
                    'message' => 'Password updated successfully.',
                ]);
            } else {
                return response()->json([
                    'message' => 'Invalid old password.',
                ], 400);
            }

        }


    }

    public function change_mode(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'role_id' => 'required'
        ]);
        
        if ($validator->fails()) {

            $errors = $validator->errors();

            return response()->json([
                'message' => 'Validation Failed',
                'data' => $errors
            ],400);


        } else {

            $user = Auth::user();
            $user->role_id = $validatedData['role_id'];
            $user->save();

            return response()->json([
                'message' => 'Mode change successfully'
            ],200);

        }

    }

    public function getProfilefields(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'role_id' => 'required',
            'email' => 'required|email'
        ]);
        
        if ($validator->fails()) {

            $errors = $validator->errors();

            return response()->json([
                'message' => 'Validation Failed',
                'data' => $errors
            ],400);


        } else {

            $validatedData =  $validator->valid();
            
            $country_options = [
                "Afghanistan", "Albania", "Algeria", "Andorra", "Angola", "Antigua and Barbuda", "Argentina", "Armenia", "Australia", "Austria", "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Belarus", "Belgium", "Belize", "Benin", "Bhutan", "Bolivia", "Bosnia and Herzegovina", "Botswana", "Brazil", "Brunei", "Bulgaria", "Burkina Faso", "Burundi", "Cabo Verde", "Cambodia", "Cameroon", "Canada", "Central African Republic", "Chad", "Chile", "China", "Colombia", "Comoros", "Congo", "Democratic Republic of the", "Congo", "Republic of the", "Costa Rica", "Croatia", "Cuba", "Cyprus", "Czech Republic", "Denmark", "Djibouti", "Dominica", "Dominican Republic", "East Timor", "Ecuador", "Egypt", "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", "Eswatini", "Ethiopia", "Fiji", "Finland", "France", "Gabon", "Gambia", "Georgia", "Germany", "Ghana", "Greece", "Grenada", "Guatemala", "Guinea", "Guinea-Bissau", "Guyana", "Haiti", "Honduras", "Hungary", "Iceland", "India", "Indonesia", "Iran", "Iraq", "Ireland", "Israel", "Italy", "Jamaica", "Japan", "Jordan", "Kazakhstan", "Kenya", "Kiribati", "Korea", "North", "Korea", "South", "Kuwait", "Kyrgyzstan", "Laos", "Latvia", "Lebanon", "Lesotho", "Liberia", "Libya", "Liechtenstein", "Lithuania", "Luxembourg", "Madagascar", "Malawi", "Malaysia", "Maldives", "Mali", "Malta", "Marshall Islands", "Mauritania", "Mauritius", "Mexico", "Micronesia", "Moldova", "Monaco", "Mongolia", "Montenegro", "Morocco", "Mozambique", "Myanmar", "Namibia", "Nauru", "Nepal", "Netherlands", "New Zealand", "Nicaragua", "Niger", "Nigeria", "North Macedonia", "Norway", "Oman", "Pakistan", "Palau", "Panama", "Papua New Guinea", "Paraguay", "Peru", "Philippines", "Poland", "Portugal", "Qatar", "Romania", "Russia"
            ];
                    $keyboard_types = [
                                        'default',
                                        'number-pad',
                                        'decimal-pad',
                                        'numeric',
                                        'email-address',
                                        'phone-pad',
                                        'url'
                                     ];
    
                    $filed_types =   [
                                        'text',
                                        'image',
                                        'number',
                                        'dropdown',
                                        'datepicker',
                                        'radio',
                                        'checkbox',
                                        'textarea'
                                    ]; 


                    if( $validatedData['role_id'] == 3)
                    {

                                $fields = [
                                        [
                                            '0',    
                                            'Full Name *',
                                            'full_name',
                                            $filed_types[0],
                                            $keyboard_types[0],
                                            [],
                                            ''
                                        ],
                                        [
                                            '1',    
                                            'Salutation',
                                            'salutation',
                                            $filed_types[3],
                                            $keyboard_types[0],
                                            ['Mr.','Mrs.','MissMs.','Dr.','Prof.','Engr'],
                                            ''
                                        ],
                                        [
                                            '2',    
                                            'Phone Number *',
                                            'phone_number',
                                            $filed_types[2],  
                                            $keyboard_types[1],
                                            [],
                                            ''
                                        ],
                                        [
                                            '3',    
                                            'LandLine',
                                            'land_line',
                                            $filed_types[0],  
                                            $keyboard_types[0],
                                            [],
                                            ''
                                        ],
                                        [
                                            '4',    
                                            'Date of Birth',
                                            'date_of_birth',
                                            $filed_types[4],
                                            $keyboard_types[1],
                                            [],
                                            ''
                                        ],
                                        [
                                            '5',    
                                            'Gender *',
                                            'gender',
                                            $filed_types[3],
                                            $keyboard_types[0],
                                            ['Male','Female','Other'],
                                            ''
                                            
                                        ],
                                        [
                                            '6',    
                                            'Martial Status',
                                            'martial_status',
                                            $filed_types[3],
                                            $keyboard_types[0],
                                            ['Married','Single','Widowed','Divorced'],
                                            ''
                                        ],
                                        [
                                            '7',    
                                            'Occupation',
                                            'occupation',
                                            $filed_types[3],
                                            $keyboard_types[0],
                                            ["Teacher", "Doctor", "Engineer", "Accountant", "Nurse", "Lawyer", "Scientist", "Artist", "Chef", "Architect", "Writer", "Musician", "Photographer", "Designer", "Programmer", "Police Officer", "Firefighter", "Pilot", "Dentist", "Pharmacist", "Electrician", "Plumber", "Carpenter", "Mechanic", "Veterinarian", "Psychologist", "Social Worker", "Economist","Farmer", "Librarian", "Graphic Designer", "Marketing Manager", "Financial Analyst", "HR Specialist", "Environmentalist", "Biologist", "Geologist", "Journalist", "Actor", "Dancer", "Athlete", "Historian", "Interior Designer", "Astronomer", "Mathematician"],
                                            ''
                                        ],

                                        [
                                            '8',    
                                            'Street',
                                            'street',
                                            $filed_types[0],
                                            $keyboard_types[0],
                                            [],
                                            ''
                                        ],
                                        [
                                            '9',    
                                            'Suburb',
                                            'suburb',
                                            $filed_types[0],
                                            $keyboard_types[0],
                                            [],
                                            ''
                                        ],
                                        [
                                            '10',    
                                            'State',
                                            'state',
                                            $filed_types[0],
                                            $keyboard_types[0],
                                            [],
                                            ''
                                        ],
                                        [
                                            '11',    
                                            'Medicare No',
                                            'medicare_no',
                                            $filed_types[2],  
                                            $keyboard_types[1],
                                            [],
                                            ''
                                        ],
                                        [
                                            '12',    
                                            'Pension / Concession No',
                                            'pension_or_Concession_no',
                                            $filed_types[2],  
                                            $keyboard_types[1],
                                            [],
                                            ''
                                        ]
                                       
                                          
                                ];

                    }
                    else if( $validatedData['role_id'] == 2)
                    {

                        $fields = [
                            [
                                '0',    
                                'Doctor Id Number *',
                                'doctor_id_number',
                                $filed_types[0],
                                $keyboard_types[0],
                                [],
                                ''
                            ],
                            [
                                '1',    
                                'Full Name *',
                                'full_name',
                                $filed_types[0],
                                $keyboard_types[0],
                                [],
                                ''
                            ],
                            [
                                '2',    
                                'Salutation',
                                'salutation',
                                $filed_types[3],
                                $keyboard_types[0],
                                ['Mr.','Mrs.','MissMs.','Dr.','Prof.','Engr'],
                                ''
                            ],
                            [
                                '3',    
                                'Phone Number *',
                                'phone_number',
                                $filed_types[2],  
                                $keyboard_types[1],
                                [],
                                ''
                            ],
                            [
                                '4',    
                                'LandLine',
                                'land_line',
                                $filed_types[2],  
                                $keyboard_types[1],
                                [],
                                ''
                            ],
                            [
                                '5',    
                                'Date of Birth',
                                'date_of_birth',
                                $filed_types[4],
                                $keyboard_types[1],
                                [],
                                ''
                            ],
                            [
                                '6',    
                                'Gender *',
                                'gender',
                                $filed_types[3],
                                $keyboard_types[0],
                                ['Male','Female','Other'],
                                ''
                                
                            ],
                            [
                                '7',    
                                'Martial Status',
                                'martial_status',
                                $filed_types[3],
                                $keyboard_types[0],
                                ['Married','Single','Widowed','Divorced'],
                                ''
                            ],
                            [
                                '8',    
                                'Occupation',
                                'occupation',
                                $filed_types[3],
                                $keyboard_types[0],
                                ["Teacher", "Doctor", "Engineer", "Accountant", "Nurse", "Lawyer", "Scientist", "Artist", "Chef", "Architect", "Writer", "Musician", "Photographer", "Designer", "Programmer", "Police Officer", "Firefighter", "Pilot", "Dentist", "Pharmacist", "Electrician", "Plumber", "Carpenter", "Mechanic", "Veterinarian", "Psychologist", "Social Worker", "Economist","Farmer", "Librarian", "Graphic Designer", "Marketing Manager", "Financial Analyst", "HR Specialist", "Environmentalist", "Biologist", "Geologist", "Journalist", "Actor", "Dancer", "Athlete", "Historian", "Interior Designer", "Astronomer", "Mathematician"],
                                ''
                            ],

                            [
                                '9',    
                                'Street',
                                'street',
                                $filed_types[0],
                                $keyboard_types[0],
                                [],
                                ''
                            ],
                            [
                                '10',    
                                'Suburb',
                                'suburb',
                                $filed_types[0],
                                $keyboard_types[0],
                                [],
                                ''
                            ],
                            [
                                '11',    
                                'State',
                                'state',
                                $filed_types[0],
                                $keyboard_types[0],
                                [],
                                ''
                            ],
                            [
                                '12',    
                                'Medicare No',
                                'medicare_no',
                                $filed_types[2],  
                                $keyboard_types[1],
                                [],
                                ''
                            ],
                            [
                                '13',    
                                'Pension / Concession No',
                                'pension_or_Concession_no',
                                $filed_types[2],  
                                $keyboard_types[1],
                                [],
                                ''
                            ]
                           
                              
                    ];
                    
                    }
                    else if( $validatedData['role_id'] == 1)
                    {

                        $fields = [
                            [
                                '1',    
                                'Gender *',
                                'gender',
                                $filed_types[3],
                                $keyboard_types[0],
                                ['Male','Female','Other'],
                                ''
                                
                            ],
                            [
                                '2',    
                                'Date of birth  *',
                                'date_of_birth',
                                $filed_types[4],
                                $keyboard_types[1],
                                [],
                                ''
                            ]
                            
                        ];                
                    }


                    $user =  User::where(['email' => $validatedData['email'], 'role_id' => $validatedData['role_id'] ])->first();

                    $userMeta  = $user->userMeta;


                     for($i=0; $i < count($fields) ;$i++)
                     {
                         for( $j=0; $j < count($userMeta) ; $j++ ) 
                         {

                             if( $fields[$i][2] == $userMeta[$j]['option'] )
                             {
                                 $fields[$i][6] = $userMeta[$j]['value'];
                             }
                         }
                     }

                     

                     $data['fields'] = $fields;
                     // $data['userMeta'] = $userMeta;
                     
                     
                     return response()->json([
                         'message' => 'Get fields successfully',
                         'data' => $data
                     ],200);
             
                }
            
    }
    
    public function storeProfile(Request $request)
    { 

        try
        {
         
            if($request->role_id == '3')
            {
                $validator = Validator::make($request->all(), [
                        'user_id' => 'nullable',
                        'role_id' => 'nullable',
                        'land_line' => 'nullable',
                        'state' => 'nullable',
                        'suburb' => 'nullable',
                        'street' => 'nullable',
                        'salutation' => 'nullable',
                        'martial_status' => 'nullable',
                        'gender' => 'required',
                        'occupation' => 'nullable',
                        'medicare_no' => 'nullable',
                        'pension_or_Concession_no' => 'nullable',
                        'date_of_birth' => 'nullable',
                        'full_name'=> 'required',
                        'phone_number'=> 'required'
                ]);
            }
            else if($request->role_id == '2'){

                $validator = Validator::make($request->all(), [
                    'user_id' => 'nullable',
                    'role_id' => 'nullable',
                    'land_line' => 'nullable',
                    'state' => 'nullable',
                    'suburb' => 'nullable',
                    'street' => 'nullable',
                    'salutation' => 'nullable',
                    'martial_status' => 'nullable',
                    'gender' => 'required',
                    'occupation' => 'nullable',
                    'medicare_no' => 'nullable',
                    'pension_or_Concession_no' => 'nullable',
                    'date_of_birth' => 'nullable',
                    'full_name'=> 'required',
                    'phone_number'=> 'required',
                    'doctor_id_number'=> 'required'  
                ]);

            }  else if($request->role_id == '1'){

                $validator = Validator::make($request->all(), [
                    'user_id' => 'required',
                    'role_id' => 'required',
                    'date_of_birth' => 'required',
                    'gender' => 'required'
                ]);

            }

        if ($validator->fails()) {

            $errors = $validator->errors();

            return response()->json([
                'message' => 'Validation Failed',
                'data' => $errors
            ],422);


        } else {
        
        $validatedData =  $validator->valid();

        if($request->role_id == '3')
            {
            $meta = $request->only([
                'land_line',
                'state',
                'suburb',
                'street',
                'salutation',
                'martial_status',
                'gender',
                'occupation',
                'medicare_no',
                'pension_or_Concession_no',
                'date_of_birth',
                'full_name',
                'phone_number'
            ]);

        }

        else if($request->role_id == '2')
        {
                $meta = $request->only([
                    'doctor_id_number',
                    'land_line',
                    'state',
                    'suburb',
                    'street',
                    'salutation',
                    'martial_status',
                    'gender',
                    'occupation',
                    'medicare_no',
                    'pension_or_Concession_no',
                    'date_of_birth',
                    'full_name',
                    'phone_number'
                ]);
                
        }
        else if($request->role_id == '1')
        {
            $meta = $request->only([
                'date_of_birth',
                'gender'
        ]);

        }
    

        if(DB::table('user_meta')->where('user_id', $validatedData['user_id'])->where('role_id', $validatedData['role_id'])->exists())
        {
            DB::table('user_meta')->where('user_id', $validatedData['user_id'])->where('role_id', $validatedData['role_id'])->delete();
        }




            $MetaFields = [];
            foreach ($meta as $option => $value) {
                if ($value !== null) {
                    $MetaFields[] = [
                        'user_id' => $validatedData['user_id'],
                        'role_id' => $validatedData['role_id'],
                        'option' => $option,
                        'value' => $value,
                        'created_at' => now()
                    ];
                }
            }
    
            DB::table('user_meta')->insert($MetaFields);

            return response()->json([
                'message' => 'Profile Saved successfully'
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

    public function getProfileData(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'role_id' => 'required',
            'user_id' => 'required'
        ]);
        
        if ($validator->fails()) {

            $errors = $validator->errors();

            return response()->json([
                'message' => 'Validation Failed',
                'data' => $errors
            ],400);


        } else {


            $validatedData =  $validator->valid();

            $where = [
                "user_id" => $validatedData['user_id'],
                "role_id" => $validatedData['role_id']
            ];

           $meta =  UserMeta::where($where)->get();

           if($meta)
           {
            $data['user_profile'] =  $meta;
    
            return response()->json([
                'message' => 'Get user profile.',
                'data' => $data
            ], 200);


           }
           else
           {
            return response()->json([
                'message' => 'No profile found.'
            ], 400);
           }

        }


    }


  
    public function RequestToReceiverStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'sender_id' => 'required',
            'receiver_id' => 'required|exists:users,id',
            'friend_and_family' => 'required',
            'relation' => 'nullable',
        ]);
        
        if ($validator->fails()) 
        {

            $errors = $validator->errors();

            return response()->json([
                'message' => 'Validation Failed',
                'data' => $errors
            ],400);


        }
        else 
        {

            $validatedData =  $validator->valid();
            $validatedData['sender_accept_or_reject'] = '1';
            $validatedData['receiver_notification'] = '1';

            DB::table('request_to_friend_and_family')->insert($validatedData);
            
            $this->save_notifications('Request To Friend And Family','The Request Send Successfully',$validatedData['sender_id'],$validatedData['receiver_id'],'request_to_friend_and_family');

            return response()->json([
                'message' => 'send request to doctor successfully'
            ],200);


        }



    }

    public function RequestToDoctor(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'patient_id' => 'required'
        ]);
        
        if ($validator->fails()) {

            $errors = $validator->errors();

            return response()->json([
                'message' => 'Validation Failed',
                'data' => $errors
            ],400);


        } else {


            $validatedData =  $validator->valid();
            $users = User::where('del','0')->where('verified','1')->where('active','1')->where('role_id','2')->get();

            if($users)
            {
                $user_data = [];

                foreach($users as $user)
                {
                    $profile = UserMeta::where(["user_id" => $user->id,"role_id" => $user->role_id])->get();
                    //$request_to_doctor = DB::table('request_to_doctor')->where('doctor_id', $user->id)->where('patient_id',$validatedData['patient_id'])->first();
                    $user_data[] = [
                        "id" => $user->id,
                        "email" => $user->email,
                        "phone_number" => $user->phone_number,
                        "active" => $user->active,
                        "created_by" => $user->created_by,
                        "role" => $user->role,
                        "verified" => $user->verified,
                        "profile" => $profile,
                        //"has_request" => $request_to_doctor ? true : false
                    ];
        
                    $data =  [
                        'user' => $user_data,
                        ];
                }

                    return response()->json([
                        'message' => 'Get Doctor list successfully',
                        'data' => $data
                    ],200);

            }
            else
            {
                return response()->json([
                    'message' => 'Doctor not found'
                ],400);
            }
        }
    }

    public function RequestToDoctorStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'patient_id' => 'required',
            'doctor_id' => 'required',
            'patient_note' => 'nullable',
            'doctor_notification' => 'required',
            'patient_accept_or_reject' => 'required'
            
            
        ]);
        
        if ($validator->fails()) 
        {

            $errors = $validator->errors();

            return response()->json([
                'message' => 'Validation Failed',
                'data' => $errors
            ],422);


        }
        else 
        {
            $validatedData =  $validator->valid();

            $startDate = Carbon::now()->subDays(15);
            $request_list_to_check =  DB::table('request_to_doctor')
            ->where([
                ['patient_id', '=', $validatedData['patient_id']],
                ['doctor_id', '=', $validatedData['doctor_id']],
                ['doctor_accept_or_reject', '!=', '2'],
                ['patient_accept_or_reject', '!=', '2'],
                ['date', '>=', $startDate]
                
            ])
            ->count();


           
                if($request_list_to_check > 0)
                {
                    return response()->json([
                        'message' => 'Request has already been made for this doctor.'
                    ],400);
                }

            
            DB::table('request_to_doctor')->insert($validatedData);
            
            $this->save_notifications('Request To Doctor','The Request Send Successfully',$validatedData['patient_id'],$validatedData['doctor_id'],'request_to_doctor');

            return response()->json([
                'message' => 'send request to doctor successfully'
            ],200);


        }



    }

    public function ShowAllRequestsToDoctor(Request $request)
    {
        // 7777
        $validator = Validator::make($request->all(), [
            'doctor_id' => 'required',
            'from' => 'nullable',            
        ]);
        
        if ($validator->fails()) 
        {

            $errors = $validator->errors();

            return response()->json([
                'message' => 'Validation Failed',
                'data' => $errors
            ],400);


        }
        else 
        {

            $validatedData =  $validator->valid();
            $startDate = Carbon::now()->subDays(15);

        $from  =    $validatedData['from'] ?? '0';
        $validatedData =  $validator->valid();
        $notifications_list = DB::table('request_to_doctor');
        $notifications_list->where('doctor_id',$validatedData['doctor_id']);
        $notifications_list->where('patient_accept_or_reject', '1');
        $notifications_list->where('doctor_accept_or_reject', '0');
        $notifications_list->whereDate('date','>=',$startDate);
        $notifications_list->orderBy('id', 'DESC');
        $notifications_list->skip($from)->take(10);
        $notifications = $notifications_list->get();

        $count  = DB::table('request_to_doctor')
        ->where('doctor_id',$validatedData['doctor_id'])
        ->where('patient_accept_or_reject', '1')
        ->where('doctor_accept_or_reject', '0')
        ->whereDate('date','>=',$startDate)
        ->count();

            foreach($notifications as $notification)
            {
                $user = User::find($notification->patient_id);

                if($user){
                    $user_data = [
                        "id" => $user->id,
                        "email" => $user->email,
                        "phone_number" => $user->phone_number,
                        "active" => $user->active,
                        "created_by" => $user->created_by,
                        "role" => $user->role,
                        "verified" => $user->verified,
                        "userMeta" => $user->userMeta
                    ];
                }
                else
                {
                    $user_data = [];
                }

                $notification->user_data = $user_data;
            }

            $data['request_to_doctor'] = $notifications;
            $data['count'] =  $count;


            return response()->json([
                'message' => 'Get all request successfully',
                'data' => $data
            ],200);


        }



    }

    public function ShowAllRequestsToDoctorRejectBYPatient(Request $request)
    {
        // 7777
        $validator = Validator::make($request->all(), [
            'doctor_id' => 'required',
            'from' => 'nullable',            
        ]);
        
        if ($validator->fails()) 
        {

            $errors = $validator->errors();

            return response()->json([
                'message' => 'Validation Failed',
                'data' => $errors
            ],400);


        }
        else 
        {

            $validatedData =  $validator->valid();
            $startDate = Carbon::now()->subDays(15);

         $from =    $validatedData['from'] ?? '0';

        $validatedData =  $validator->valid();
        $notifications_list = DB::table('request_to_doctor');
        $notifications_list->where('doctor_id',$validatedData['doctor_id']);
        $notifications_list->where('patient_accept_or_reject', '2');
        $notifications_list->orderBy('id', 'DESC')->skip($from)->take(10);
        $notifications = $notifications_list->get();

        $count = DB::table('request_to_doctor')
        ->where('doctor_id',$validatedData['doctor_id'])
        ->where('patient_accept_or_reject', '1')
        ->where('doctor_accept_or_reject', '0')
        ->whereDate('date', '<=', $startDate)
        ->count();

            foreach($notifications as $notification)
            {
                $user = User::find($notification->patient_id);

                if($user){
                    $user_data = [
                        "id" => $user->id,
                        "email" => $user->email,
                        "phone_number" => $user->phone_number,
                        "active" => $user->active,
                        "created_by" => $user->created_by,
                        "role" => $user->role,
                        "verified" => $user->verified,
                        "userMeta" => $user->userMeta
                    ];
                }
                else
                {
                    $user_data = [];
                }

                $notification->user_data = $user_data;
            }

            $data['request_to_doctor'] = $notifications;
            $data['count'] =  $count;


            return response()->json([
                'message' => 'Get all request successfully',
                'data' => $data
            ],200);


        }



    }

    public function ShowAllRequestsToDoctorRejectBYDoctor(Request $request)
    {
        // 7777
        $validator = Validator::make($request->all(), [
            'doctor_id' => 'required',
            'from' => 'nullable',            
        ]);
        
        if ($validator->fails()) 
        {

            $errors = $validator->errors();

            return response()->json([
                'message' => 'Validation Failed',
                'data' => $errors
            ],400);


        }
        else 
        {

            $validatedData =  $validator->valid();
        $startDate = Carbon::now()->subDays(15);

        $from  = $validatedData['from'] ?? '0';
        $validatedData =  $validator->valid();
        $notifications_list = DB::table('request_to_doctor');
        $notifications_list->where('doctor_id',$validatedData['doctor_id']);
        $notifications_list->where('doctor_accept_or_reject', '2');
        $notifications_list->orderBy('id', 'DESC')->skip($from)->take(10);
        $notifications = $notifications_list->get();

        $count = DB::table('request_to_doctor')
        ->where('doctor_id',$validatedData['doctor_id'])
        ->where('doctor_accept_or_reject', '2')
        ->count();

            foreach($notifications as $notification)
            {
                $user = User::find($notification->patient_id);

                if($user){
                    $user_data = [
                        "id" => $user->id,
                        "email" => $user->email,
                        "phone_number" => $user->phone_number,
                        "active" => $user->active,
                        "created_by" => $user->created_by,
                        "role" => $user->role,
                        "verified" => $user->verified,
                        "userMeta" => $user->userMeta
                    ];
                }
                else
                {
                    $user_data = [];
                }

                $notification->user_data = $user_data;
            }

            $data['request_to_doctor'] = $notifications;
            $data['count'] =  $count;


            return response()->json([
                'message' => 'Get all request successfully',
                'data' => $data
            ],200);


        }



    }

    public function ShowAllRequestsToFriendAndFamily(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required'
            
        ]);
        
        if ($validator->fails()) 
        {

            $errors = $validator->errors();

            return response()->json([
                'message' => 'Validation Failed',
                'data' => $errors
            ],400);


        }
        else 
        {
            $validatedData =  $validator->valid();


            $notifications = DB::table('request_to_friend_and_family')
            ->where('receiver_id',$validatedData['user_id'])
            ->orWhere('sender_id',$validatedData['user_id'])
            ->get();

            foreach($notifications as $notification)
            {
                $receiver = User::find($notification->receiver_id);
                if($receiver  && $notification->receiver_id == $validatedData['user_id'] ){

                    $receiver_data = [
                        "id" => $receiver->id,
                        "email" => $receiver->email,
                        "phone_number" => $receiver->phone_number,
                        "active" => $receiver->active,
                        "created_by" => $receiver->created_by,
                        "role" => $receiver->role,
                        "verified" => $receiver->verified,
                        "userMeta" => $receiver->userMeta,
                    ];

                }
                else
                {
                    $receiver_data = [];
                }

                $sender = User::find($notification->sender_id);
                if($sender && $notification->sender_id == $validatedData['user_id']){

                    $sender_data = [
                        "id" => $sender->id,
                        "email" => $sender->email,
                        "phone_number" => $sender->phone_number,
                        "active" => $sender->active,
                        "created_by" => $sender->created_by,
                        "role" => $sender->role,
                        "verified" => $sender->verified,
                        "userMeta" => $sender->userMeta,
                    ];

                }
                else
                {
                    
                    $sender_data = [];
                }

                $notification->receiver_data = $receiver_data;
                $notification->sender_data = $sender_data;
            }

            $data = ['request_to_user' => $notifications];

            return response()->json([
                'message' => 'Get all request successfully',
                'data' => $data
            ],200);


        }



    }

    public function ShowAllRequestsToPatient(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'patient_id' => 'required'
            
        ]);
        
        if ($validator->fails()) 
        {

            $errors = $validator->errors();

            return response()->json([
                'message' => 'Validation Failed',
                'data' => $errors
            ],400);


        }
        else 
        {
            $validatedData =  $validator->valid();


            $notifications = DB::table('request_to_doctor')
            ->where('patient_id',$validatedData['patient_id'])
            ->get();

            foreach($notifications as $notification)
            {
                $user = User::find($notification->doctor_id);
                if($user){
                    $user_data = [
                        "id" => $user->id,
                        "email" => $user->email,
                        "phone_number" => $user->phone_number,
                        "active" => $user->active,
                        "created_by" => $user->created_by,
                        "role" => $user->role,
                        "verified" => $user->verified,
                        "userMeta" => $user->userMeta,
                    ];
                }
                else
                {
                    $user_data = [];
                }

                $notification->user_data = $user_data;
            }

            $data = ['request_to_doctor' => $notifications];

            return response()->json([
                'message' => 'Get all request successfully',
                'data' => $data
            ],200);


        }



    }

    public function ShowRequestsToDoctorById(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required'
            
        ]);
        
        if ($validator->fails()) 
        {

            $errors = $validator->errors();

            return response()->json([
                'message' => 'Validation Failed',
                'data' => $errors
            ],400);


        }
        else 
        {
            $validatedData =  $validator->valid();


            $notification = DB::table('request_to_doctor')
            ->where('id',$validatedData['id'])
            ->first();


          
                $user = User::find($notification->patient_id);

                if($user){
                    $user_data = [
                        "id" => $user->id,
                        "email" => $user->email,
                        "phone_number" => $user->phone_number,
                        "active" => $user->active,
                        "created_by" => $user->created_by,
                        "role" => $user->role,
                        "verified" => $user->verified,
                        "userMeta" => $user->userMeta
                    ];
                }
                else
                {
                    $user_data = [];
                }

                $notification->user_data = $user_data;
            
            $modules = DB::table('module_manager')->where('role_id', '3')->get();

            $module_manager = [];
            $i = 0;
            foreach($modules as $module)
            {
                $module_manager[$i]['module_name'] = $module->name;
                $module_manager[$i]['module_data'] = DB::table($module->table_name)->where('del', '!=' ,'1')->where('user__id', $notification->patient_id)->get();
                $i++;
            }

            $notification->module_manager = $module_manager;


            $data = ['request_to_doctor_detail' => $notification];

            return response()->json([
                'message' => 'Get all request for doctor successfully',
                'data' => $data
            ],200);


        }



    }

    public function RequestStatusUpdateSender(Request $request)
    {
       
        $validator = Validator::make($request->all(), [
            'notification_id' => 'required',
            'sender_accept_or_reject' => 'required'
        ]);
        
        
        if ($validator->fails()) {

            $errors = $validator->errors();

            return response()->json([
                'message' => 'Validation Failed',
                'data' => $errors
            ],400);


        } else {
          
          $validatedData =  $validator->valid();

          DB::table('request_to_friend_and_family')->where('id',$validatedData['notification_id'])->update(['sender_accept_or_reject' => $validatedData['sender_accept_or_reject']]);

          $request_to_sender=   DB::table('request_to_friend_and_family')->where('id',$validatedData['notification_id'])->first();

            if($validatedData['sender_accept_or_reject'] == "1")
            {
               $this->save_notifications('Accepted Your Request','The Request Accepted Successfully',$request_to_sender->sender_id,$request_to_sender->receiver_id,'request_to_friend_and_family_sender_accepted');
            }
            else if($validatedData['sender_accept_or_reject'] == "2")
            {
              $this->save_notifications('Rejected Your Request','The Request Rejected Successfully',$request_to_sender->sender_id,$request_to_sender->receiver_id,'request_to_friend_and_family_sender_rejected');
            }
            
            return response()->json([
                'message' => 'Friend and family`s request status updated successfully'
            ],200);
            
        }        
    }

    public function RequestStatusUpdateReceiver(Request $request)
    {
       
        $validator = Validator::make($request->all(), [
            'notification_id' => 'required',
            'receiver_accept_or_reject' => 'required'
        ]);
        
        
        if ($validator->fails()) {

            $errors = $validator->errors();

            return response()->json([
                'message' => 'Validation Failed',
                'data' => $errors
            ],400);


        } else {
          
          $validatedData =  $validator->valid();

          DB::table('request_to_friend_and_family')->where('id',$validatedData['notification_id'])->update(['receiver_accept_or_reject' => $validatedData['receiver_accept_or_reject']]);

          $request_to_receiver =   DB::table('request_to_friend_and_family')->where('id',$validatedData['notification_id'])->first();

            if($validatedData['receiver_accept_or_reject'] == "1")
            {
               $this->save_notifications('Accepted Your Request','The Request Accepted Successfully',$request_to_receiver->receiver_id,$request_to_receiver->sender_id,'request_to_friend_and_family_receiver_accepted');
            }
            else if($validatedData['receiver_accept_or_reject'] == "2")
            {
              $this->save_notifications('Rejected Your Request','The Request Rejected Successfully',$request_to_receiver->receiver_id,$request_to_receiver->sender_id,'request_to_friend_and_family_receiver_rejected');
            }
            
            return response()->json([
                'message' => 'Friend and family`s request status updated successfully'
            ],200);
            
        }        
    }

    public function RequestStatusUpdateDoctor(Request $request)
    {
       
        $validator = Validator::make($request->all(), [
            'notification_id' => 'required',
            'doctor_note' => 'nullable',
            'doctor_accept_or_reject' => 'required'
        ]);
        
        
        if ($validator->fails()) {

            $errors = $validator->errors();

            return response()->json([
                'message' => 'Validation Failed',
                'data' => $errors
            ],400);


        } else {
          
            $validatedData =  $validator->valid();

            DB::table('request_to_doctor')->where('id',$validatedData['notification_id'])->update(['doctor_note'=> $validatedData['doctor_note'], 'doctor_accept_or_reject' => $validatedData['doctor_accept_or_reject']]);
            $request_to_doctor =   DB::table('request_to_doctor')->where('id',$validatedData['notification_id'])->first();

            if($validatedData['doctor_accept_or_reject'] == "1")
            {
                DB::table('request_to_doctor')->where('id',$validatedData['notification_id'])->update(['accepted_date' => date('d-m-Y')]);

               $this->save_notifications('Doctor Accepted Your Request','The Request Accepted Successfully',$request_to_doctor->doctor_id,$request_to_doctor->patient_id,'request_to_doctor_accepted');
            }
            else if($validatedData['doctor_accept_or_reject'] == "2")
            {
                DB::table('request_to_doctor')->where('id',$validatedData['notification_id'])->update(['rejected_date' => date('d-m-Y')]);
              $this->save_notifications('Doctor Rejected Your Request','The Request Rejected Successfully',$request_to_doctor->doctor_id,$request_to_doctor->patient_id,'request_to_doctor_rejected');
            }
            
            return response()->json([
                'message' => 'Notification status updated successfully'
            ],200);
            
        }        
    }

    public function RequestStatusUpdatePatient(Request $request)
    {
       
        $validator = Validator::make($request->all(), [
            'notification_id' => 'required',
            'patient_note' => 'nullable',
            'patient_accept_or_reject' => 'required'
        ]);
        
        
        if ($validator->fails()) {

            $errors = $validator->errors();

            return response()->json([
                'message' => 'Validation Failed',
                'data' => $errors
            ],400);


        } else {
          
            $validatedData =  $validator->valid();

            DB::table('request_to_doctor')->where('id',$validatedData['notification_id'])->update(['patient_note'=> $validatedData['patient_note'], 'patient_accept_or_reject' => $validatedData['patient_accept_or_reject']]);

                  $request_to_doctor =   DB::table('request_to_doctor')->where('id',$validatedData['notification_id'])->first();

                    if($validatedData['patient_accept_or_reject'] == "1")
                    {
                       $this->save_notifications('Patient Accepted Your Request','The Request Accepted Successfully',$request_to_doctor->patient_id,$request_to_doctor->doctor_id,'request_to_doctor_accepted_by_patient');
                    }
                    else if($validatedData['patient_accept_or_reject'] == "2")
                    {
                      $this->save_notifications('Patient Rejected Your Request','The Request Rejected Successfully',$request_to_doctor->patient_id,$request_to_doctor->doctor_id,'request_to_doctor_rejected_by_patient');
                    }
            return response()->json([
                'message' => 'Notification status updated successfully'
            ],200);
            
        }        
    } 
    
    public function GetNotifications(Request $request)
    { //7777
       
        $validator = Validator::make($request->all(), [
            'from' => 'required',
            'to' => 'required',
            'id' => 'required'
        ]);
        
        
        if ($validator->fails()) {

            $errors = $validator->errors();

            return response()->json([
                'message' => 'Validation Failed',
                'data' => $errors
            ],400);


        } else {
          
            $validatedData =  $validator->valid();
            
            $notifications = DB::table('notifications')->where('receiver',$validatedData['id'])->orWhere('sender',$validatedData['id'])->orderBy('id', 'DESC')->skip($validatedData['from'])->take($validatedData['to'])->get();
            
            $count = DB::table('notifications')->where('receiver',$validatedData['id'])->orWhere('sender',$validatedData['id'])->count();

            $data['notifications'] = $notifications;
            $data['count'] = $count;
            return response()->json([
                'message' => 'Notification get successfully',
                'data' => $data
            ],200);
            
        }
        
    }
    
    public function AllNotificationsMarkAsRead(Request $request)
    {
       
        $validator = Validator::make($request->all(), [
            'id' => 'required'
        ]);
        
        
        if ($validator->fails()) {

            $errors = $validator->errors();

            return response()->json([
                'message' => 'Validation Failed',
                'data' => $errors
            ],400);


        } else {
          
            $validatedData =  $validator->valid();
            
            DB::table('notifications')->where('receiver',$validatedData['id'])->update(['seen_by_receiver'=>1]);
            DB::table('notifications')->where('sender',$validatedData['id'])->update(['seen_by_sender'=>1]);
            
            return response()->json([
                'message' => 'All notifications mark as read successfully',
                
            ],200);
            
        }
        
    }
    
    public function SingleNotificationsMarkAsRead(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'notification_id'  => 'required'
        ]);
        
        
        if ($validator->fails()) {

            $errors = $validator->errors();

            return response()->json([
                'message' => 'Validation Failed',
                'data' => $errors
            ],400);


        } else {
          
            $validatedData =  $validator->valid();
            
            DB::table('notifications')->where(['receiver' =>$validatedData['id'],'id'=>$validatedData['notification_id']])->update(['seen_by_receiver'=>1]);
            DB::table('notifications')->where(['sender'=>$validatedData['id'],'id' =>$validatedData['notification_id']])->update(['seen_by_sender'=>1]);
            
            return response()->json([
                'message' => 'Notification mark as read successfully',
                
            ],200);
            
        }
        
    }
    
    public function CountNotifications(Request $request)
    {
       
        $validator = Validator::make($request->all(), [
            'id' => 'required'
        ]);
        
        
        if ($validator->fails()) {

            $errors = $validator->errors();

            return response()->json([
                'message' => 'Validation Failed',
                'data' => $errors
            ],400);


        } else {
          
            $validatedData =  $validator->valid();
            
            $notifications = 0;
            $notifications += DB::table('notifications')->where('receiver',$validatedData['id'])->where('seen_by_receiver','0')->count();
            $notifications += DB::table('notifications')->where('sender',$validatedData['id'])->where('seen_by_sender','0')->count();

            $data['count'] = $notifications;
            
            return response()->json([
                'message' => 'Notification Count successfully',
                'data' => $data
            ],200);
            
        }
        
    }


    public function getEmergencyContactsfields(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'role_id' => 'required',
            'email' => 'required|email'
        ]);
        
        if ($validator->fails()) {

            $errors = $validator->errors();

            return response()->json([
                'message' => 'Validation Failed',
                'data' => $errors
            ],400);


        } else {

            $validatedData =  $validator->valid();
            
            $country_options = [
                "Afghanistan", "Albania", "Algeria", "Andorra", "Angola", "Antigua and Barbuda", "Argentina", "Armenia", "Australia", "Austria", "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Belarus", "Belgium", "Belize", "Benin", "Bhutan", "Bolivia", "Bosnia and Herzegovina", "Botswana", "Brazil", "Brunei", "Bulgaria", "Burkina Faso", "Burundi", "Cabo Verde", "Cambodia", "Cameroon", "Canada", "Central African Republic", "Chad", "Chile", "China", "Colombia", "Comoros", "Congo", "Democratic Republic of the", "Congo", "Republic of the", "Costa Rica", "Croatia", "Cuba", "Cyprus", "Czech Republic", "Denmark", "Djibouti", "Dominica", "Dominican Republic", "East Timor", "Ecuador", "Egypt", "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", "Eswatini", "Ethiopia", "Fiji", "Finland", "France", "Gabon", "Gambia", "Georgia", "Germany", "Ghana", "Greece", "Grenada", "Guatemala", "Guinea", "Guinea-Bissau", "Guyana", "Haiti", "Honduras", "Hungary", "Iceland", "India", "Indonesia", "Iran", "Iraq", "Ireland", "Israel", "Italy", "Jamaica", "Japan", "Jordan", "Kazakhstan", "Kenya", "Kiribati", "Korea", "North", "Korea", "South", "Kuwait", "Kyrgyzstan", "Laos", "Latvia", "Lebanon", "Lesotho", "Liberia", "Libya", "Liechtenstein", "Lithuania", "Luxembourg", "Madagascar", "Malawi", "Malaysia", "Maldives", "Mali", "Malta", "Marshall Islands", "Mauritania", "Mauritius", "Mexico", "Micronesia", "Moldova", "Monaco", "Mongolia", "Montenegro", "Morocco", "Mozambique", "Myanmar", "Namibia", "Nauru", "Nepal", "Netherlands", "New Zealand", "Nicaragua", "Niger", "Nigeria", "North Macedonia", "Norway", "Oman", "Pakistan", "Palau", "Panama", "Papua New Guinea", "Paraguay", "Peru", "Philippines", "Poland", "Portugal", "Qatar", "Romania", "Russia"
            ];
                    $keyboard_types = [
                                        'default',
                                        'number-pad',
                                        'decimal-pad',
                                        'numeric',
                                        'email-address',
                                        'phone-pad',
                                        'url'
                                     ];
    
                    $filed_types =   [
                                        'text',
                                        'image',
                                        'number',
                                        'dropdown',
                                        'datepicker',
                                        'radio',
                                        'checkbox',
                                        'textarea'
                                    ]; 


                    if( $validatedData['role_id'] == 3)
                    {

                        $fields = [
                            [
                                '0',    
                                'Name *',
                                'name',
                                $filed_types[0],
                                $keyboard_types[0],
                                [],
                                ''
                                
                            ],
                            [
                                '1',    
                                'Relationship *',
                                'relationship',
                                $filed_types[0],
                                $keyboard_types[0],
                                [],
                                ''
                                
                            ],
                            [
                                '2',    
                                'Contact Number *',
                                'contact_number',
                                $filed_types[0],
                                $keyboard_types[0],
                                [],
                                ''
                                
                            ]
                            
                        ];    

                    }
                    else if( $validatedData['role_id'] == 2)
                    {

                        $fields = [
                            [
                                '0',    
                                'Name *',
                                'name',
                                $filed_types[0],
                                $keyboard_types[0],
                                [],
                                ''
                                
                            ],
                            [
                                '1',    
                                'Relationship *',
                                'relationship',
                                $filed_types[0],
                                $keyboard_types[0],
                                [],
                                ''
                                
                            ],
                            [
                                '2',    
                                'Contact Number *',
                                'contact_number',
                                $filed_types[0],
                                $keyboard_types[0],
                                [],
                                ''
                                
                            ],
                            
                        ];    
                    
                    }
                    else if( $validatedData['role_id'] == 1)
                    {

                        $fields = [
                            [
                                '0',    
                                'Name *',
                                'name',
                                $filed_types[0],
                                $keyboard_types[0],
                                [],
                                ''
                                
                            ],
                            [
                                '1',    
                                'Relationship *',
                                'relationship',
                                $filed_types[0],
                                $keyboard_types[0],
                                [],
                                ''
                                
                            ],
                            [
                                '2',    
                                'Contact Number *',
                                'contact_number',
                                $filed_types[0],
                                $keyboard_types[0],
                                [],
                                ''
                                
                            ],
                            
                            
                        ];                
                    }


                    $user =  User::where(['email' => $validatedData['email'], 'role_id' => $validatedData['role_id'] ])->first();

                    $emergency_contacts  = DB::table('emergency_contacts')->where('role_id',$user->role_id)->where('user_id',$user->id)->get();

                    $emergency_contacts = json_decode(json_encode($emergency_contacts), true);

                     for($i=0; $i < count($fields) ;$i++)
                     {
                         for( $j=0; $j < count($emergency_contacts) ; $j++ ) 
                         {

                             if( $fields[$i][2] == $emergency_contacts[$j]['option'] )
                             {
                                 $fields[$i][6] = $emergency_contacts[$j]['value'];
                             }
                         }
                     }

                     

                     $data['fields'] = $fields;
                     // $data['userMeta'] = $userMeta;
                     
                     
                     return response()->json([
                         'message' => 'Get fields successfully',
                         'data' => $data
                     ],200);
             
                }
            
    }

    public function storeEmergencyContactsfields(Request $request)
    { 

        try
        {
         
            if($request->role_id == '3')
            {
                $validator = Validator::make($request->all(), [
                    'user_id' => 'required',
                    'role_id' => 'required',
                    'contact_number' => 'required',
                    'relationship' => 'required',
                    'name' => 'required'  
                ]);
            }
            else if($request->role_id == '2'){

                $validator = Validator::make($request->all(), [
                    'user_id' => 'required',
                    'role_id' => 'required',
                    'contact_number' => 'required',
                    'relationship' => 'required',
                    'name' => 'required'
                ]);

            }  else if($request->role_id == '1'){

                $validator = Validator::make($request->all(), [
                    'user_id' => 'required',
                    'role_id' => 'required',
                    'contact_number' => 'required',
                    'relationship' => 'required',
                    'name' => 'required'
                ]);

            }

        if ($validator->fails()) {

            $errors = $validator->errors();

            return response()->json([
                'message' => 'Validation Failed',
                'data' => $errors
            ],422);


        } else {
        
        $validatedData =  $validator->valid();

        if($request->role_id == '3')
            {
            $meta = $request->only([
                        'contact_number',
                        'relationship',
                        'name'
            ]);

        }

        else if($request->role_id == '2')
        {
                    $meta = $request->only([
                        'contact_number',
                        'relationship',
                        'name'
                    ]);
                
        }
        else if($request->role_id == '1')
        {
            $meta = $request->only([
                'contact_number',
                'relationship',
                'name'
                ]);

        }
    

        if(DB::table('emergency_contacts')->where('user_id', $validatedData['user_id'])->where('role_id', $validatedData['role_id'])->exists())
        {
            DB::table('emergency_contacts')->where('user_id', $validatedData['user_id'])->where('role_id', $validatedData['role_id'])->delete();
        }


            $MetaFields = [];
            foreach ($meta as $option => $value) {
                if ($value !== null) {
                    $MetaFields[] = [
                        'user_id' => $validatedData['user_id'],
                        'role_id' => $validatedData['role_id'],
                        'option' => $option,
                        'value' => $value,
                        'created_at' => now()
                    ];
                }
            }
    
            DB::table('emergency_contacts')->insert($MetaFields);

            return response()->json([
                'message' => 'Emergency contacts saved successfully'
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

    public function getEmergencyContactsData(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'role_id' => 'required',
            'user_id' => 'required'
        ]);
        
        if ($validator->fails()) {

            $errors = $validator->errors();

            return response()->json([
                'message' => 'Validation Failed',
                'data' => $errors
            ],400);


        } else {


            $validatedData =  $validator->valid();

            $where = [
                "user_id" => $validatedData['user_id'],
                "role_id" => $validatedData['role_id']
            ];

           $meta = DB::table('emergency_contacts')->where($where)->get();


           if($meta)
           {
            $data['emergency_contacts'] =  $meta;
    
            return response()->json([
                'message' => 'Get emergency contacts',
                'data' => $data
            ], 200);


           }
           else
           {
            return response()->json([
                'message' => 'No profile found.'
            ], 400);
           }

        }


    }

    public function myPatient(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'doctor_id' => 'required',
            'from' => 'nullable',           
        ]);
        
        if ($validator->fails()) 
        {

            $errors = $validator->errors();

            return response()->json([
                'message' => 'Validation Failed',
                'data' => $errors
            ],400);


        }
        else 
        {
            $validatedData =  $validator->valid();

            $from  = $validatedData['from'] ?? '0';

            $notifications = DB::table('request_to_doctor')
            ->where('doctor_id',$validatedData['doctor_id'])
            ->where('doctor_accept_or_reject',1)
            ->where('patient_accept_or_reject',1)
            ->orderBy('id', 'DESC')->skip($from)->take(10)
            ->get();

            $count = DB::table('request_to_doctor')
            ->where('doctor_id',$validatedData['doctor_id'])
            ->where('doctor_accept_or_reject',1)
            ->where('patient_accept_or_reject',1)
            ->count();



            foreach($notifications as $notification)
            {

                $user = User::find($notification->patient_id);

                if($user){
                    $user_data = [
                        "id" => $user->id,
                        "email" => $user->email,
                        "phone_number" => $user->phone_number,
                        "active" => $user->active,
                        "created_by" => $user->created_by,
                        "role" => $user->role,
                        "verified" => $user->verified,
                        "userMeta" => $user->userMeta
                    ];
                }
                else
                {
                    $user_data = [];
                }

                $notification->user_data = $user_data;


            }

            $data['my_patient'] = $notifications;
            $data['count'] =  $count;

            return response()->json([
                'message' => 'Get all request successfully',
                'data' => $data
            ],200);


        }



    }

    public function myPatientAll(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'doctor_id' => 'required',           
        ]);
        
        if ($validator->fails()) 
        {

            $errors = $validator->errors();

            return response()->json([
                'message' => 'Validation Failed',
                'data' => $errors
            ],400);


        }
        else 
        {
            $validatedData =  $validator->valid();

            $notifications = DB::table('request_to_doctor')
            ->where('doctor_id',$validatedData['doctor_id'])
            ->where('doctor_accept_or_reject',1)
            ->where('patient_accept_or_reject',1)
            ->select('patient_id')
            ->groupBy('patient_id')
            ->get();

            return response()->json([$notifications],200);
        }



    }

    public function counterFunction(Request $request)
    {

        
        $mostUsed = "";

        if($request->date == 'total')
        {
            $user = User::where('del', '0')->where('role_id','!=','1')->count();
        }
        else
        {
            $dateFromRequest = Carbon::parse($request->date);
            $dateFromRequest->setTime(0, 0, 0);
            $user = User::where('del', '0')->whereDate('created_at', '>=', $dateFromRequest)->where('role_id','!=','1')->count();
        }
        

        return response()->json([
            'message' => 'Get counts for dashboard',
            'user' => $user,
            'patient' => User::where('del','0')->where('role_id','3')->count(),
            'doctors' => User::where('del','0')->where('role_id','2')->count(),
            'pending_verification' => User::where('del','0')->where('verified','0')->where('role_id','!=','1')->count(),
            'medication' => DB::table('a_medicine')->where('del','0')->count(),
            'disorders' => DB::table('a_disorders')->where('del','0')->count(),
            'adverse_effects' => DB::table('a_adverse_effects')->where('del','0')->count(),
        ],200);

    }


    public function getNoOfRecords(Request $request)
    {
            $html =  "<table>
                         <tr>
                        <th>Name</th>";

            $mostUsed = [];
        
        if($request->name == "medicine")
        {
            $mostUsed = DB::table('a_medicine')
                            ->join('p_medication', 'a_medicine.id', '=', 'p_medication.medication')
                            ->where('a_medicine.del','0')
                            ->where('p_medication.del','0')
                            ->select('a_medicine.name as name', DB::raw('COUNT(p_medication.user__id) as total_users'))
                            ->groupBy('name')
                            ->orderByDesc('total_users')
                            ->limit($request->no_of_records)
                            ->get();

            $html .= "<th>Total Medicines Using</th>";
        }
        else if($request->name == "adverse_effect")
        {
            $mostUsed = DB::table('a_adverse_effects')
                            ->join('p_adverse_effects', 'a_adverse_effects.id', '=', 'p_adverse_effects.adverse_effect')
                            ->where('a_adverse_effects.del','0')
                            ->where('p_adverse_effects.del','0')
                            ->select('a_adverse_effects.name as name', DB::raw('COUNT(p_adverse_effects.user__id) as total_users'))
                            ->groupBy('name')
                            ->orderByDesc('total_users')
                            ->limit($request->no_of_records)
                            ->get();

            $html .= "<th>Total Adverse Effect</th>";
        }
        else if($request->name == "disorder")
        {
            $mostUsed = DB::table('a_disorders')
                            ->join('p_disorders', 'a_disorders.id', '=', 'p_disorders.disorder')
                            ->where('a_disorders.del','0')
                            ->where('p_disorders.del','0')
                            ->select('a_disorders.name as name', DB::raw('COUNT(p_disorders.user__id) as total_users'))
                            ->groupBy('name')
                            ->orderByDesc('total_users')
                            ->limit($request->no_of_records)
                            ->get();

            $html .= "<th>Total Disorder</th>";
        }

        $html .="</tr>";


        if(count($mostUsed) == 0)
        {
            $html .="<tr>
              <td  colspan='2' style=' text-align: center;' >no records found</td>
            </tr>";
        }
        else
        {
            foreach($mostUsed as $m)
            {
                $html .="  <tr>
                <td>".$m->name."</td>
                <td>".$m->total_users."</td>
                </tr>";
            }
        }

        $html .="</table>";

        return $html;


    }


}
