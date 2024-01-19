<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Auth;
use App\Models\User;
use App\Models\UserMeta;
use App\Models\Role;
use App\Models\ModuleManager;
use Exception;
use App\Http\Controllers\EmailController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;





class AuthorizationController extends Controller
{

    public function register(Request $request)
    {
        try
        {
            $validator = Validator::make($request->all(), [
                'role_id' => 'required',
                'email' => 'required',
                'password' => 'required|confirmed',
                'created_by' => 'required',
                'phone_number' => 'required',
                'term_and_conditions' => 'accepted',
                
            ]);
            
            if ($validator->fails()) {

                $errors = $validator->errors();

                return response()->json([
                    'message' => 'Validation Failed',
                    'data' => $errors
                ],422);


            } else {
            
            $validatedData =  $validator->valid();

                $where = [
                        "email"=>$validatedData['email'],
                        "role_id"=>$validatedData['role_id']
                ];

            if(User::where($where)->count() > 0)
            {

                return response()->json([
                    'message' => 'This email has already been register with this role.'
                ],400);

            }

            $user_id = User::insertGetId([
                    'role_id' => $validatedData['role_id'],
                    'email' =>   $validatedData['email'],
                    'password' => Hash::make($validatedData['password']),
                    'phone_number' => $validatedData['phone_number'],
                    'active' => '1',
                    'verified' => $validatedData['created_by'] == '1' ? '1' : '0',
                    'created_by' => $validatedData['created_by'],
                    'created_at' => now()
                ]);


                if($validatedData['created_by'] == '1')
                {
                    return response()->json([
                        'message' => 'User created successfully'
                    ],200);
                }

                $credentials = $request->only('role_id','email', 'password');

                if(Auth::attempt($credentials))
                {
                            
                    $user = Auth::user();

                    $token = $user->createToken('plainTextToken')->plainTextToken;

                    $user_data = [
                        "id" => $user->id,
                        "email" => $user->email,
                        "phone_number" => $user->phone_number,
                        "created_by" =>  $user->created_by,
                        "role" => $user->role,
                        "verified" => $user->verified
                    ];
                    
                    $data =  [
                            'user' => $user_data,
                            'token' => $token
                            ];
        
        
                    $request->session()->put('user',$user_data);
                    $request->session()->put('token',$token);

                    $otp_return_msg =  $this->sendOTP($user->id,'Email verification');
            
                    return response()->json([
                        'message' => $otp_return_msg,
                        'data' => $data
                    ],200);

                }
                else
                {
                    return response()->json([
                        'message' => 'Please login once'
                    ],400);
                }

                
            }  

        }        
        catch(Exception $e) 
        {
            return response()->json([
                'message' => $e->getMessage()
            ],500);
        }

    }

    public function sendOTP($id,$subject)
    {
           $user = User::where('id',$id)->exists();

            if($user){

                $user_details = User::where('id',$id)->first();
                $digits = 4;
                $otp_code =  rand(pow(10, $digits-1), pow(10, $digits)-1); 
                User::where('id', $id)->update([
                    'remember_token' => $otp_code
                ]);      
                
                $params = [
                    'subject' => $subject.' | Dcotor App',
                    'to' => $user_details->email,
                    'blade' => 'otp_email',
                    'data' => [
                        'email' => $user_details->email,
                        'otp_code' => $otp_code,
                        'subject' => $subject
                        ]
                ];
                
               
                $emailController = new EmailController();
                $emailSent = $emailController->SendInstantEmail($params);
                if($emailSent){
                    return 'Email has been sent, Please check your Email.';
                    
                }else{
                    return 'SMTP is not Working, Try Later!';
                    
                }
                
            }else{
                return 'Account with the entered email does not exsit.';
            }

        
    }

    public function codeVerification(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'role_id' => 'required',
            'email' => 'required',
            'code' => 'required'            
        ]);
        
        if ($validator->fails()) {

            $errors = $validator->errors();

            return response()->json([
                'message' => 'Validation Failed',
                'data' => $errors
            ],422);


        } else {

            $validatedData =  $validator->valid();

            $where = [
                        'role_id' => $validatedData['role_id'],
                        'email' => $validatedData['email'],
                        'remember_token' => $validatedData['code']
                     ];

            if(User::where($where)->count() < 1)
            {

                return response()->json([
                    'message' => 'Verification code is mismatch'
                ],400);

            }

            User::where($where)->update([
                'remember_token' => '',
                'verified' => '1'
            ]);


            return response()->json([
                'message' => 'Verified successfully'
            ],200);

        }
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
            'role_id' => 'required',
        ]);

        if ($validator->fails()) {

            $errors = $validator->errors();

            return response()->json([
                'message' => 'Validation Failed',
                'data' => $errors
            ],422);


        } else {
          
           $validatedData =  $validator->valid();

           if (Auth::attempt($validatedData)) {
            
            $user = Auth::user();

            if($user->active != '1')
            {
                return response()->json([
                    'message' => 'The account is currently inactive'
                ],400);
            }

            if($user->del != '0')
            {
                return response()->json([
                    'message' => 'The account has been deleted'
                ],400);
            }


            $token = $user->createToken('plainTextToken')->plainTextToken;

            $has_profile = UserMeta::where(["user_id" => $user->id,"role_id" => $user->role_id])->count() ? true : false;

            $user_data = [
                "id" => $user->id,
                "email" => $user->email,
                "phone_number" => $user->phone_number,
                "created_by" =>  $user->created_by,
                "role" => $user->role,
                "verified" => $user->verified,
                "has_profile" => $has_profile,
                
            ];

            

            
            
            $data =  [
                    'user' => $user_data,
                    'token' => $token,
                    'total_session_time_in_seconds' => env('SESSION_LIFETIME')*60
                     ];

                     $Permission_list = DB::table('permission')
                     ->where('role_id', $user->role_id)
                     ->select('name')
                     ->get();
                     $permission_arr = [];
                     
                     foreach($Permission_list as $p)
                     {
                        $permission_arr[] = $p->name;
                     }

            $request->session()->put('user',$user_data);
            $request->session()->put('token',$token);
            $request->session()->put('permission_list',$permission_arr);


            if($user->verified == "0")
            { 
                $otp_return_msg =  $this->sendOTP($user->id,'Email verification');
                return response()->json([
                    'message' => $otp_return_msg,
                    'data' => $data
                ],200);

    
            }

            return response()->json([
                'message' => 'Login successfully',
                'data' => $data
            ],200);

       
        } else {


            return response()->json([
                'message' => 'Invalid credentials'
            ],400);
        }


        }
        
    }

    public function logout(Request $request)
    {
        $user = $request->user();
        $user->tokens()->delete();
        Session::flush();
    
        return response()->json([
            'message' => 'Logged out successfully'
        ],200);
    }

    public function updatePassword(Request $request)
    {

        $validator = Validator::make($request->all(), [
          'password' => 'required|confirmed',
          'token' => 'required'
        ]);

        if ($validator->fails()) {

            $errors = $validator->errors();

            return response()->json([
                'message' => 'Validation Failed',
                'data' => $errors
            ],400);


        } else {

                        // Check if the token exists and is not expired
                    $reset = DB::table('password_resets')
                    ->where('token', $request->token)
                    ->first();

            if(!$reset) {
            return response()->json([
                'message' => 'Invalid or expired reset password link!',
            ],404);
            }

            // Get the user's email address from the password_resets table
            $email = $reset->email;

            // Update the user's password
            $user = User::where('email', $email)->first();
            $user->password = Hash::make($request->password);
            $user->save();

            // Delete the token from the password_resets table
            DB::table('password_resets')->where('email', $email)->delete();

            return response()->json([
                'message' => 'Password reset successfully',
            ],200);
           


        }

       
    }


    public function forgetPasswordSendOTP(Request $request)
    {
        try
        {
            $validator = Validator::make($request->all(), [
                'role_id' => 'required',
                'email' => 'required'
            ]);


            if ($validator->fails()) {

                $errors = $validator->errors();

                return response()->json([
                    'message' => 'Validation Failed',
                    'data' => $errors
                ],422);


            } else {
            
                $validatedData =  $validator->valid();

                $user = User::where($validatedData)->first();

                if($user)
                {
                    $otp_return_msg =  $this->sendOTP($user->id,'Forget Password');

            
                    
                    return response()->json([
                        'message' => $otp_return_msg
                    ],200);
                }
                else
                {
                    return response()->json([
                        'message' => "This user does not exist"
                    ],400);
                }



            }


        }        
        catch(Exception $e) 
        {
            return response()->json([
                'message' => $e->getMessage()
            ],500);
        }

    }

    public function forgetPasswordChange(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required',
            'password' => 'required'           
        ]);
        
        if ($validator->fails()) {

            $errors = $validator->errors();

            return response()->json([
                'message' => 'Validation Failed',
                'data' => $errors
            ],422);


        } else {

            $validatedData =  $validator->valid();

            $where = [
                        'remember_token' => $validatedData['code']
                     ];

            if(User::where($where)->count() < 1)
            {

                return response()->json([
                    'message' => 'OTP does not match'
                ],400);

            }

            User::where($where)->update([
                'remember_token' => '',
                'password' => Hash::make($validatedData['password'])
            ]);


            return response()->json([
                'message' => 'Password successfully'
            ],200);

        }
    }


}
