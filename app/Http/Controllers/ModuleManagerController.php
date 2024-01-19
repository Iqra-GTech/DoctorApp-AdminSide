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
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Collection;
use App\Http\Controllers\SocialHistoryController;




class ModuleManagerController extends Controller
{

    public function index(Request $request)
    { 
        $module_managers_quary = ModuleManager::query();

      if(Auth::user()->role_id != '1')
      {
        $module_managers_quary->where("role_id",Auth::user()->role_id)->where("active","1");
        $recordsFiltered = $recordsTotal = ModuleManager::where("role_id",Auth::user()->role_id)->where("active","1")->count();
      }
      else
      {
        $recordsFiltered = $recordsTotal = ModuleManager::count();
      }

      if($request->filled('filter'))
      {
        [$module_managers_quary , $recordsFiltered] =  $this->filter($request->filter,$module_managers_quary,$recordsFiltered);
      }


      $start = $request->input('start', 0);
      $length = $request->input('length', 10);
      $module_managers_quary->offset($start)->limit($length);
      $module_managers_quary->orderBy('id', 'desc');
      $module_managers = $module_managers_quary->get();
      

        if($module_managers)
        {
            $module_managers_data = [];

            foreach($module_managers as $module_manager)
            {
                $module_managers_data[] = [
                    "id" => $module_manager->id,
                    "name" => $module_manager->name,
                    "table_name" => $module_manager->table_name,
                    "active" => $module_manager->active,
                    "created_by" => $module_manager->created_by,
                    "module_icon" => url('/images/'. $module_manager->module_icon),
                    "module_header_icon" => url('/images/'. $module_manager->module_header_icon),
                    "role" => $module_manager->role
                ];
    

            }

            $data['module_manager'] = $module_managers_data;

                return response()->json([
                    'message' => 'Get Module Manager list successfully',
                    'data' => $data,
                    'recordsFiltered' => $recordsFiltered,
                    'recordsTotal' => $recordsTotal,
                    'draw' => intval($request->draw)
                    
                ],200);

        }
        else
        {
            return response()->json([
                'message' => 'Module Manager not found'
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

    public function create()
    {
        //
    }

    public function comma_separated_values_array_from_linked_table(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'dependency' => 'required',
            'dependency_values' => 'required',
            'dependency_options' => 'required'
        ]);
        
        

        if ($validator->fails()) {

            $errors = $validator->errors();

            return response()->json([
                'message' => 'Validation Failed',
                'data' => $errors
            ],422);


        } else {
          
           $validatedData =  $validator->valid();

          $dependency =  $validatedData['dependency'];
          $dependency_values = $validatedData['dependency_values'];
           $dependency_options  = $validatedData['dependency_options'];


           
           if($dependency == "users")
           {
               $where = [
                   'verified' =>'1',
                   'active' =>'1',
                   'del' => '0'
                  ];

               if($dependency_values == "id")
               {
                   $response_array =  DB::table('users')->where($where)->where('role_id', '!=' , '1')->get();
               }
               else
               {
                   $role_id =  explode(".",$dependency_values)[1];
                   $where = [
                       'role_id' =>$role_id
                   ];

                   $response_array =  DB::table('users')->where($where)->get();
               }
                                       
           }
           else
           {
               $response_array =  DB::table($dependency)->get();
           }
           
           $response_array = json_decode($response_array, true);

           $arr  = [];
    
           for($i=0;$i<count($response_array); $i++)
           {
               $arr[$i]["value"] =   "".$response_array[$i]['id'];
               $arr[$i]["label"] =   "".$response_array[$i][$dependency_options];
           }


        return response()->json([
            'message' => 'Get Module Manager successfully',
            'data' => $arr
        ],200);

        }

    }

    public function commaSeparatedValuesInArray($moduleManagerMeta)
    {
        
        $moduleManagerMeta->each(function ($meta) {
            
            if($meta->type == "dropdown" || $meta->type == "checkbox" || $meta->type == "radio")
            {
                if($meta->import_option == "1")
                {
                    $comma_separated_values =  $meta->comma_separated_values;
                    
                    $comma_separated_values = explode(",",$comma_separated_values);
                    
                    $arr  = [];
                    
                    for($i=0;$i<count($comma_separated_values); $i++)
                    {
                        $arr[$i]["value"] =   $comma_separated_values[$i];
                        $arr[$i]["label"] =   $comma_separated_values[$i];
                    }
                    
                    $meta->comma_separated_values = $arr;
                }
                else
                {
                    if($meta->dependency == "users")
                    {
                        $where = [
                            'verified' =>'1',
                            'active' =>'1',
                            'del' => '0'
                           ];

                        if($meta->dependency_values == "id")
                        {
                            $response_array =  DB::table('users')->where($where)->where('role_id', '!=' , '1')->get();
                        }
                        else
                        {
                            $role_id =  explode(".",$meta->dependency_values)[1];
                            $where = [
                                'role_id' =>$role_id
                            ];

                            $response_array =  DB::table('users')->where($where)->get();
                        }
                                                
                    }
                    else
                    {
                        $response_array =  DB::table($meta->dependency)->where(['del' => '0'])->get();
                    }
                    
                    $response_array = json_decode($response_array, true);

                    $arr  = [];
             
                    for($i=0;$i<count($response_array); $i++)
                    {
                        $arr[$i]["value"] =   $response_array[$i]['id'];
                        $arr[$i]["label"] =   $response_array[$i][$meta->dependency_options];
                    }

                    $meta->comma_separated_values = $arr;
                    
                }
                
            }
            
        });
    
        return $moduleManagerMeta;
    }
    
    public function show($id)
    {

        $module_manager = ModuleManager::find($id);

        if($module_manager)
        {
            $module_managers_data = [
                "id" => $module_manager->id,
                "name" => $module_manager->name,
                "table_name" => $module_manager->table_name,
                "active" => $module_manager->active,
                "created_by" => $module_manager->created_by,
                "module_icon" => url('/images/'. $module_manager->module_icon),
                "module_header_icon" => url('/images/'. $module_manager->module_header_icon),
                "role" => $module_manager->role,
                "module_manager_meta" => $this->commaSeparatedValuesInArray($module_manager->ModuleManagerMeta)
            ];

            $data =  [
                'module_manager' => $module_managers_data,
                ];

                return response()->json([
                    'message' => 'Get Module Manager successfully',
                    'data' => $data
                ],200);

        }
        else
        {
            return response()->json([
                'message' => 'Module Manager not found'
            ],400);
        }
        
    }


    public function getModuleManagersFields($id)
    {
        $module_manager_meta =  ModuleManagerMeta::where('module_id',$id)->get();

        if($module_manager_meta)
        {
            $data =  [
                'module_manager_meta' => $module_manager_meta,
                ];

                return response()->json([
                    'message' => 'Get module manager fields successfully',
                    'data' => $data
                ],200);

        }
        else
        {
            return response()->json([
                'message' => 'Module Manager not found'
            ],400);
        }
        
    }

    public function edit($module_id)
    { 
        $module_manager = ModuleManager::find($module_id);

        if($module_manager)
        {
            $module_manager_meta =  ModuleManagerMeta::where('module_id',$module_id)->first();
            return view("Admin.Modules.edit", ["module_manager"=>$module_manager,"module_manager_meta"=>$module_manager_meta]);
        }
        else
        {
            abort(404);
        }
        
    }

    public function update(Request $request, $id)
    {
        
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'active' => 'required',
            'table_name' => 'required',
            'role_id' => 'required'
        ]);
        
        if ($validator->fails()) {

            $errors = $validator->errors();

            return response()->json([
                'message' => 'Validation Failed',
                'data' => $errors
            ],422);


        } else {
          
           $validatedData =  $validator->valid();

            ModuleManager::where('id', $id)->update([
            'name' => $validatedData['name'],
            'active' => $validatedData['active'],
            'table_name' => $validatedData['table_name'],
            'role_id' => $validatedData['role_id']
            ]);

            $module_manager_meta = $request->module_manager_meta;

            if(filled($module_manager_meta))
            {
                for($i=0; $i < count($module_manager_meta); $i++ )
                {
                    $module_manager_meta_data[] = [];

                    foreach ($module_manager_meta[$i] as $key => $value) {

                        $module_manager_meta_data[$i][$key] = $value;
                    }

                    $module_manager_meta_data[$i]['dependency'] = $validatedData['table_name'];
                    $module_manager_meta_data[$i]['module_id'] = $id;
                    $module_manager_meta_data[$i]['created_at'] = now();

                }
                
                ModuleManagerMeta::where('module_id', $id)->delete();

                ModuleManagerMeta::insert($module_manager_meta_data);
                
                
            }
            
    
    
            return response()->json([
                'message' => 'Module Manager Save successfully'
            ],200);
            
        }        

    }

    public function store(Request $request)
    {
        try {
        
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'table_name' => 'required',
            'role_id' => 'required',
            'module_icon' => 'required|image|mimes:jpeg,png,jpg,gif',
            'module_header_icon' => 'required|image|mimes:jpeg,png,jpg,gif'
        ]);
        
        if ($validator->fails()) {

            $errors = $validator->errors();

            return response()->json([
                'message' => 'Validation Failed',
                'data' => $errors
            ],422);


        } else {
          
           $validatedData =  $validator->valid();

           if ($request->hasFile('module_icon')) {
                $image = $request->file('module_icon');
                $imageName = time() . '1.' . $image->getClientOriginalExtension();
                $image->move(public_path('images'), $imageName);
            }

            if ($request->hasFile('module_header_icon')) {
                $image_ = $request->file('module_header_icon');
                $imageName_ = time() . '2.' . $image_->getClientOriginalExtension();
                $image_->move(public_path('images'), $imageName_);
            }
            
            $set_table_name = $validatedData['role_id'] == '1' ? 'a_' : '';
            $set_table_name .= $validatedData['role_id'] == '2' ? 'd_' : '';
            $set_table_name .= $validatedData['role_id'] == '3' ? 'p_' : '';
            $set_table_name .= $this->clean($validatedData['table_name']);

            if (Schema::hasTable($set_table_name)) {

                return response()->json([
                    'message' => 'Table with this name already exists'
                ],400);

            }


            if(count($request->option) <=0)
            {
                return response()->json([
                    'message' => 'Please create at least one field'
                ],400);
            }


           // $file = $request->file;
            
            $option = $request->option;
            $type = $request->type;
            $value = $request->value;
            $required = $request->required;
            $import_option = $request->import_option;
            $table_name_for_field = $request->table_name_for_field; // table name of the join table
            $tables_column_name_value = $request->tables_column_name_value;  // column name of the join table which is going to use in value="***" of dropdwon 
            $tables_column_name_show = $request->tables_column_name_show;  // column name of the join table which is going to use as showing test in each  option <option>****<option/>
            $comma_separated_values = $request->comma_separated_values;
            $table_name_for_field_clean= '';
            $tables_column_name_value_clean= '';
            $sql_string = '';
            $check_array = ['id','module__id','user__id','show__to','del','_Verify_','hide__or__show'];

            if(filled($option) && filled($type) && filled($required) && filled($import_option))
            {
                for($i = 0; $i < count($option); $i++)
                {
                    if(in_array($this->clean($option[$i]),$check_array) == true)
                    {
                        return response()->json([
                            'message' => 'Field with name "'.$option[$i].'" already exists'
                        ],400);
                    }
                    else
                    {
                        $check_array[] = $this->clean($option[$i]);
                    }
                }
            }
            
        
           
            


            $module_manager_id = ModuleManager::insertGetId([
                'name' => $validatedData['name'],
                'active' => '1',
                'table_name' => $set_table_name,
                'role_id' => $validatedData['role_id'],
                'created_by' => '1',
                'created_at' => now(),
                'module_icon' => $imageName,
                'module_header_icon' => $imageName_
                ]);

                

            if(filled($option) && filled($type) && filled($required) && filled($import_option))
            {
                for($i = 0; $i < count($option); $i++)
                {
                    $table_name_for_field_clean = $this->clean($table_name_for_field[$i]);
                    $tables_column_name_value_clean = $this->clean($tables_column_name_value[$i]);

                    ModuleManagerMeta::insert([
                        'module_id' => $module_manager_id,
                        'type' => $type[$i],
                        'option' => $this->clean($option[$i]),
                        'value' => $this->clean_but_not_numbers($value[$i]),
                        'required' => $required[$i],
                        'dependency' => $table_name_for_field[$i] ?? "",
                        'dependency_values' => $tables_column_name_value[$i] ?? "",
                        'dependency_options' => $tables_column_name_show[$i] ?? "",
                        'import_option' => $import_option[$i],
                        'comma_separated_values' => $this->clean_comma_separated_values($comma_separated_values[$i]),
                        'created_at' => now()
                    ]);

                    $sql_string .= '`';
                    $sql_string .= $this->clean($option[$i]);
                    $sql_string .= '`';
                    $sql_string .= " text,";
                }

                $sql_string .= "`module__id` text,";
                $sql_string .= "`hide__or__show` text,";
                $sql_string .= "`_Verify_` text,";
                $sql_string .= "`user__id` text,";
                $sql_string .= "`show__to` text,";
                $sql_string .= "`del` text";
            }

                DB::statement('CREATE TABLE `'.$set_table_name.'` ( `id` INT(225) UNSIGNED AUTO_INCREMENT PRIMARY KEY,'.$sql_string.')'
                );


                if (Schema::hasTable($set_table_name) == false) {

                    DB::table('module_manager')->where('id',$module_manager_id)->delete();
                    DB::table('module_mata')->where('module_id',$module_manager_id)->delete(); 
                }
            
            return response()->json([
                'message' => 'Module Manager Save Successfully'
            ],200);
            
        } 
        
        }  catch(Exception $e)  {
   
       
        if($module_manager_id){
            DB::table('module_manager')->where('id',$module_manager_id)->delete();
            DB::table('module_mata')->where('module_id',$module_manager_id)->delete(); 
        }  

        if (Schema::hasTable($set_table_name)) {

            Schema::drop($set_table_name);
        }
        
        return response()->json([
            'message' => $e->getMessage()
        ],500); 

        }

    }

    public function deleteField(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'field_id' => 'required',
            'table_name' => 'required',
            'role_id' => 'required',
            'module_id' => 'required',
            'column_name' => 'required'
        ]);
        

        if ($validator->fails()) {

            $errors = $validator->errors();

            return response()->json([
                'message' => 'Validation Failed',
                'data' => $errors
            ],422);


        } else {
          
           $validatedData =  $validator->valid();


           DB::statement("ALTER TABLE `".$validatedData['table_name']."` DROP COLUMN `".$this->clean($validatedData['column_name'])."` ");

           DB::table('module_mata')->where('id',$validatedData['field_id'])->delete();

            return response()->json([
                'message' => 'delete Successfully'
            ],200);

        }
    }

    public function saveOrUpdateSingleField(Request $request)
    {
   
      
        try {
        
        $validator = Validator::make($request->all(), [
            'module_id' => 'required',
            'field_id' => 'required',
            'role_id' => 'required',
            'table_name' => 'required',
            'option' => 'required',
            'old_option' => 'nullable',
            'type' => 'required',
            'required' => 'required',
            'value' => 'nullable',
            'import_option' => 'required',
            'tables_column_name_value' => 'nullable',
            'tables_column_name_show' => 'nullable',
            'table_name_for_field' => 'nullable',
            'comma_separated_values' => 'nullable',
        ]);
        
        

        if ($validator->fails()) {

            $errors = $validator->errors();

            return response()->json([
                'message' => 'Validation Failed',
                'data' => $errors
            ],422);


        } else {
          
           $validatedData =  $validator->valid();           

           if ($request->hasFile('module_icon')) {
                $image = $request->file('module_icon');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('images'), $imageName);
            }

           
            $columns_list =   Schema::getColumnListing($validatedData['table_name']);


            if($validatedData['field_id'] == 0 )
            {
                if (Schema::hasColumn($validatedData['table_name'],$this->clean($validatedData['option']))) {
                    return response()->json([
                        'message' => $this->clean($validatedData['option']).' column already exists in the table.'
                    ],400);
                } 
                
                DB::statement("ALTER TABLE `".$validatedData['table_name']."`  ADD  `".$this->clean($validatedData['option'])."`  text");

               $module_last_insert_id = DB::table('module_mata')->insertGetId(
                [
                    'module_id' => $validatedData['module_id'],
                    'option' => $this->clean($validatedData['option']),
                    'type' => $validatedData['type'],
                    'required' => $validatedData['required'],
                    'value' => $this->clean_but_not_numbers($validatedData['value']),
                    'import_option' => $validatedData['import_option'],
                    'dependency_values' => $validatedData['tables_column_name_value']  ?? "",
                    'dependency_options' => $validatedData['tables_column_name_show']  ?? "",
                    'dependency' => $validatedData['table_name_for_field']  ?? "",
                    'comma_separated_values' => $this->clean_comma_separated_values($validatedData['comma_separated_values']),
                    'created_at' => now()
                ]);

                return response()->json([
                    'message' => 'Fields Saved Successfully',
                    'module_last_insert_id' => $module_last_insert_id
                ],200);
            }
            else
            {

                DB::statement("ALTER TABLE `".$validatedData['table_name']."` CHANGE `".$this->clean($validatedData['old_option'])."`  `".$this->clean($validatedData['option'])."` text");

                DB::table('module_mata')
                ->where([
                    'module_id' => $validatedData['module_id'],
                    'id' => $validatedData['field_id']
                ])
                ->update(
                        [
                            'option' => $this->clean($validatedData['option']),
                            'type' => $validatedData['type'],
                            'required' => $validatedData['required'],
                            'value' => $this->clean_but_not_numbers($validatedData['value']),
                            'import_option' => $validatedData['import_option'],
                            'dependency_values' => $validatedData['tables_column_name_value'],
                            'dependency_options' => $validatedData['tables_column_name_show'],
                            'dependency' => $validatedData['table_name_for_field'],
                            'comma_separated_values' => $this->clean_comma_separated_values($validatedData['comma_separated_values'])
                        ]
                       );

                       return response()->json([
                        'message' => 'Fields Updated Successfully',
                        'module_last_insert_id' => 0
                    ],200);

            } 
            
        } 
        
        }  catch(Exception $e)  {
                    
        return response()->json([
            'message' => $e->getMessage()
        ],500); 

        }

    }
    
public function updateUpperFields(Request $request)
{

    $validator = Validator::make($request->all(), [
        'module_name' => 'nullable',
        'role_id' => 'nullable',
        'module_id' => 'required',
        'module_icon' => 'nullable|mimes:jpeg,png,jpg,gif',
        'module_header_icon' => 'nullable|mimes:jpeg,png,jpg,gif'
    ]);
    
    

    if ($validator->fails()) {

        $errors = $validator->errors();

        return response()->json([
            'message' => 'Validation Failed',
            'data' => $errors
        ],422);


    } else {
      
      $validatedData =  $validator->valid();

      if($validatedData['module_name'] != "")
      {
        DB::table("module_manager")
        ->where('id',$validatedData['module_id'])
        ->update(['name' => $validatedData['module_name']]);
      }

      if ($request->hasFile('module_icon')) 
        {

            $image = $request->file('module_icon');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'), $imageName);
            
            DB::table("module_manager")
            ->where(['id' => $validatedData['module_id']])
            ->update(['module_icon' => $imageName]);
        }

        if ($request->hasFile('module_header_icon')) 
        {

            $image = $request->file('module_header_icon');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'), $imageName);
            
            DB::table("module_manager")
            ->where(['id' => $validatedData['module_id']])
            ->update(['module_header_icon' => $imageName]);
        }
        
        if($validatedData['role_id'] != "")
        {
            DB::table("module_manager")
            ->where('id',$validatedData['module_id'])
            ->update(['role_id' => $validatedData['role_id']]);
        }
        
         return response()->json([
            'message' => 'Updated successfully'
        ],200);

    }     

}

    function clean($string) {

        $string = str_replace(' ', '_', $string);
        $string =  preg_replace('/[^A-Za-z_\-]/', '', $string);
        $string = strtolower($string);
        $string = str_replace('-', '', $string); 
        return $string;
        
    }

    function clean_but_not_numbers($string) {

        $string = str_replace(' ', '_', $string);
        $string =  preg_replace('/[^A-Za-z0-9_\-]/', '', $string);
        $string = strtolower($string);
        $string = str_replace('-', '', $string); 
        return $string;
        
    } 

    function clean_comma_separated_values($string) {

        $string = str_replace(' ', '_', $string);
        $string =  preg_replace('/[^A-Za-z0-9_,\-]/', '', $string);
        $string = strtolower($string);
        $string = str_replace('-', '', $string); 
        return $string;
        
    }

    public function destroy(ModuleManager $module_manager){

        $module_manager = ModuleManager::where('id', $module_manager->id)->first();

        Schema::dropIfExists($module_manager->table_name);

        ModuleManager::where('id', $module_manager->id)->delete();

        ModuleManagerMeta::where('module_id', $module_manager->id)->delete();

        return response()->json([
            'message' => 'Module Manager deleted successfully'
        ],200);


    }

    public function getTables(Request $request){

        $tables_list = Schema::getConnection()->getDoctrineSchemaManager()->listTableNames();

        $not_allowed = [ 
                    'personal_access_tokens',
                    'password_resets',
                    'user_meta',
                    'module_mata',
                    'module_manager',
                    'migrations',
                    'failed_jobs',
                    'module_history',
                    "emergency_contacts",
                    "general_settings",
                    "notifications",
                    "pages",
                    "reminders",
                    "request_to_doctor",
                    "request_to_friend_and_family",
                    "request_updates",
                    "roles",
                    "social_history",
                    "sponsers",
                    "supports"
        ];

            $tables = [];
        for($i=0; $i < count($tables_list); $i++)
        {
              if(!in_array($tables_list[$i], $not_allowed))
              {
                $tables[] = $tables_list[$i];
              }
        }




       $data = ['tables' => $tables];

        return response()->json([
            'message' => 'Get tables Name Successfully',
            'data' => $data
        ],200);


    }

    public function getColumns(Request $request){

       
        $validator = Validator::make($request->all(), [
            'table_name' => 'required',
        ]);
        
        if ($validator->fails()) {

            $errors = $validator->errors();

            return response()->json([
                'message' => 'Validation Failed',
                'data' => $errors
            ],422);


        } else {


            $validatedData =  $validator->valid();

            $columns_list =   Schema::getColumnListing($validatedData['table_name']);


            $not_allowed = [ 
                'del',
                'status',
                'created_at',
                'updated_at',
                'active',
                'remember_token',
                'verified',
                'password',
                'created_by',
                '_Verify_',
                'hide__or__show'
        ];

            $columns = [];

        for($i=0; $i < count($columns_list); $i++)
        {
              if(!in_array($columns_list[$i], $not_allowed))
              {
                $columns[] = $columns_list[$i];
              }
        }

            $data = ['columns' => $columns];

            return response()->json([
                'message' => 'Get Columns Name Successfully',
                'data' => $data
            ],200);


        }



    }

    public function updateStatus(Request $request, $id)
    {
       
        $validator = Validator::make($request->all(), [
            'active' => 'required'
        ]);
        
        if ($validator->fails()) {

            $errors = $validator->errors();

            return response()->json([
                'message' => 'Validation Failed',
                'data' => $errors
            ],422);


        } else {
          
            $validatedData =  $validator->valid();

            $module_manager = ModuleManager::find($id);

           $module_manager->active = $validatedData['active'];
           $module_manager->save();

            return response()->json([
                'message' => 'Module status updated successfully'
            ],200);
            
        }        
    }

    public function sectionDataInsert(Request $request){

        try {
       
        $validator = Validator::make($request->all(), [
            'user__id'  => 'required',
            'table_name' => 'required',
        ]);
        
        if ($validator->fails()) {

            $errors = $validator->errors();

            return response()->json([
                'message' => 'Validation Failed',
                'data' => $errors
            ],422);


        } else {


            $validatedData =  $validator->valid();

            $columns_list =   Schema::getColumnListing($validatedData['table_name']);

            $insert_data = [];




           

        for($i=0; $i < count($columns_list); $i++)
        {
            if(isset($validatedData[$columns_list[$i]]))
            {
              

              /////////////

               if($request->hasFile($columns_list[$i])){

                    $imageName = time() . '.' .$image->getClientOriginalExtension();
                    $image->move(public_path('images'), $imageName);
                    $insert_data[$columns_list[$i]] =  $imageName;

               }
               else
               {

                    $insert_data[$columns_list[$i]] = $validatedData[$columns_list[$i]];
               
               }

               ////////////
            }
        }

        $insert_data['hide__or__show'] = '1';
        $insert_data['_Verify_'] = ($request->_Verify_== "0" ||  $request->_Verify_== ""  ) ? '0' : $request->_Verify_;
        
       $table_name_tb =  DB::table($validatedData['table_name'])->insert($insert_data);
       
        if($table_name_tb)
        {
            return response()->json([
                'message' => 'Data saved successfully',
            ],200);
        }
        else
        {
                return response()->json([
                    'message' => 'Something went wrong',
                ],500);
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

    public function sectionDataFatch(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'user__id' => 'required',
            'table_name' => 'required',
            'fillter' => 'nullable',
            'from' => 'nullable',
            'module_id'=> 'required'
        ]);
        
        if ($validator->fails()) {

            $errors = $validator->errors();

            return response()->json([
                'message' => 'Validation Failed',
                'data' => $errors
            ],422);


        } else {

            $validatedData =  $validator->valid();

            $fillter_dropdown = $this->fillter_dropdown($validatedData['table_name'],$validatedData['module_id']);

            $from = $validatedData['from'] ?? 0;

            $query = DB::table($validatedData['table_name']);
            $query->where('del', '!=' , '1');
            $query->orderBy('id', 'DESC');
            $query->where('user__id', $validatedData['user__id']);

            if($request->filled('filter'))
            {
                $query = $this->sectionDataFillter($request->filter,$query);           
            }

            $countIt = $query->count();
            $query->skip($from)->take(10);
            $table_data =   $query->get();

            $data = [];



            for($i=0;$i<count($table_data); $i++)
            {
                foreach($table_data[$i] as $key => $value)
                {
                    
                    $meta = DB::table('module_mata')
                    ->where('module_id',$validatedData['module_id'])
                    ->where('option',$key)
                    ->first();
    
                    if($meta && in_array($meta->type, ['checkbox','radio','dropdown']) && $meta->import_option == '0')
                    {   
                        $where[$meta->dependency_values] = $value;

                        if($meta->dependency == "users" && $meta->dependency_values != "id")
                        {        
                            $role_id =  explode(".",$meta->dependency_values)[1];
                            $where = [
                                'role_id' => $role_id
                            ];
                        }
                        
                        $table_result =  DB::table($meta->dependency)->where($where)->select($meta->dependency_options)->first();
                        $table_result = get_object_vars($table_result);
                        $data[$i][$key] = $table_result[$meta->dependency_options];
                    }
                    else
                    {
                        $data[$i][$key] = $value; 
                    }
    
    
                    
    
                }


            }

            

            return response()->json([
                'message' => 'Section data fatch successfully.',
                'data' =>  $data,
                'count' => $countIt,
                'fillter_dropdown' => $fillter_dropdown
            ],200);


        }
    }

    public function fillter_dropdown($table_name,$module_id)
    {
       $module_mata =  DB::table('module_mata')->where('module_id',$module_id)->get();
       $arr = [];
       $i = 0;
       foreach($module_mata as $m)
       {
            $option = str_replace("_"," ",$m->option);
            $option = ucwords($option);
            $arr[$i]['label'] = $option;

            if($m->import_option == 0 && in_array($m->type, ['checkbox','radio','dropdown']))
            {
              $arr[$i]['value'] = $m->option.'.'.$m->dependency.'.'.$m->dependency_options;
            }
            else
            {
              $arr[$i]['value'] = $m->option;
            }
            $i++;
       }

       

       return $arr; 

    }
 
    public function sectionDataFillter($filter,$quary)
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
                    
                }
                else
                {
                    $quary->where($f['key'], 'like', '%' . $f['value'] . '%');
                }
            }
        }
        return $quary;
    }

    
    public function sectionDataEdit(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'id' => 'required',
            'table_name' => 'required',
            'module_manager_id' => 'required'
        ]);
        
        if ($validator->fails()) {

            $errors = $validator->errors();

            return response()->json([
                'message' => 'Validation Failed',
                'data' => $errors
            ],422);


        } else {

            $validatedData =  $validator->valid();
            
            $module_manager = ModuleManager::find($validatedData['module_manager_id']);

            if($module_manager)
            {
                $module_managers_data = [
                    "id" => $module_manager->id,
                    "name" => $module_manager->name,
                    "table_name" => $module_manager->table_name,
                    "active" => $module_manager->active,
                    "created_by" => $module_manager->created_by,
                    "module_icon" => url('/images/'. $module_manager->module_icon),
                    "role" => $module_manager->role,
                    "module_manager_meta" => $this->commaSeparatedValuesInArray($module_manager->ModuleManagerMeta)
                ];
                
                
                $table_data =   (array) DB::table($validatedData['table_name'])->where(['id' => $validatedData['id']])->first();

                    for($i=0; $i < count($module_managers_data['module_manager_meta']) ;$i++)
                    {
                        
                        //7777
                        if($module_managers_data['module_manager_meta'][$i]->type == 'dropdown' && $module_managers_data['module_manager_meta'][$i]->import_option == '0')
                        {
                            
                            if($module_managers_data['module_manager_meta'][$i]->dependency == 'users')
                            {
                                
                                $store_value_tb =  (array) DB::table($module_managers_data['module_manager_meta'][$i]->dependency)
                                ->where([ 'id' => $table_data[$module_managers_data['module_manager_meta'][$i]->option] ])
                                ->first();
                            }
                            else
                            {
                                
                                $store_value_tb =  (array) DB::table($module_managers_data['module_manager_meta'][$i]->dependency)
                                ->where([ $module_managers_data['module_manager_meta'][$i]->dependency_values => $table_data[$module_managers_data['module_manager_meta'][$i]->option] ])
                                ->first();
                            }
                            
                             $module_managers_data['module_manager_meta'][$i]->store_value =  $store_value_tb[$module_managers_data['module_manager_meta'][$i]->dependency_options]; //$table_data[$module_managers_data['module_manager_meta'][$i]->option];////$store_value_tb;//[$module_managers_data['module_manager_meta'][$i]->option];
                            
                            
                        }
                        else
                        {
                            $module_managers_data['module_manager_meta'][$i]->store_value = $table_data[$module_managers_data['module_manager_meta'][$i]->option];
                        }
                    }
                
    
                $data =  [
                    'module_manager' => $module_managers_data,
                    ];
    
                    return response()->json([
                        'message' => 'Get Module Manager Data For Update',
                        'data' => $data
                    ],200);
    
            }
            else
            {
                return response()->json([
                    'message' => 'Module Manager not found'
                ],400);
            }
            
        }

        
    }

    public function sectionDataUpdate(Request $request){
        try {
       
        $validator = Validator::make($request->all(), [
            'id'  => 'required',
            'table_name' => 'required',
        ]);
        
        if ($validator->fails()) {

            $errors = $validator->errors();

            return response()->json([
                'message' => 'Validation Failed',
                'data' => $errors
            ],422);


        } else {


            $validatedData =  $validator->valid();

            $columns_list =   Schema::getColumnListing($validatedData['table_name']);

            $updated_data = [];


           

        for($i=0; $i < count($columns_list); $i++)
        {
                if(isset($validatedData[$columns_list[$i]]))
                {
                    $updated_data[$columns_list[$i]] = $validatedData[$columns_list[$i]];
                }
        }

        unset($updated_data['id']);

       
        $table_name_tb =  DB::table($validatedData['table_name'])
                            ->where('id',$validatedData['id'])
                            ->update($updated_data);
       
            return response()->json([
                'message' => 'Section data updated successfully',
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

    public function sectionDataDestroy(Request $request){

        try {
       
                $validator = Validator::make($request->all(), [
                    'id'  => 'required',
                    'table_name' => 'required',
                ]);
                
                if ($validator->fails()) {
        
                    $errors = $validator->errors();
        
                    return response()->json([
                        'message' => 'Validation Failed',
                        'data' => $errors
                    ],422);
        
        
                } else {

                    $validatedData =  $validator->valid();

                    DB::table($validatedData['table_name'])
                    ->where('id',$validatedData['id'])
                    ->update(['del' => '1']);
            
                    return response()->json([
                        'message' => 'Section data deleted successfully'
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


    public function sectionDataHideOrShow(Request $request){

        try {
       
                $validator = Validator::make($request->all(), [
                    'id'  => 'required',
                    'table_name' => 'required',
                    'hide__or__show' => 'required'
                ]);
                
                if ($validator->fails()) {
        
                    $errors = $validator->errors();
        
                    return response()->json([
                        'message' => 'Validation Failed',
                        'data' => $errors
                    ],422);
        
        
                } else {

                    $validatedData =  $validator->valid();

                    DB::table($validatedData['table_name'])->where('id',$validatedData['id'])->update(['hide__or__show' => $validatedData['hide__or__show']]);
            
                    return response()->json([
                        'message' => 'Section data status successfully'
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

    public function sectionDataShowToDoctor(Request $request)
    {

        $validator = Validator::make($request->all(),[
            'user__id' => 'required',
            'table_name' => 'required'
        ]);
        
        if ($validator->fails()) {

            $errors = $validator->errors();

            return response()->json([
                'message' => 'Validation Failed',
                'data' => $errors
            ],422);


        } else {

            $validatedData =  $validator->valid();

            $data =   DB::table($validatedData['table_name'])
            ->where('del', '!=' , '1')
            ->where('user__id', $validatedData['user__id'])
            ->get();



            return response()->json([
                'message' => 'Show Section data to doctor successfully.',
                'data' =>  $data
            ],200);


        }
    }

    public function uploadCsvGet(Request $request){
   
        $validator = Validator::make($request->all(), [
            'table_name' => 'required',
            'module_id' => 'required',
            'from' => 'required',
            'fillter' => 'nullable',
        ]);
        
        if ($validator->fails()) {

            $errors = $validator->errors();

            return response()->json([
                'message' => 'Validation Failed',
                'data' => $errors
            ],422);

 
        } else {


            $validatedData =  $validator->valid();

            $fillter_dropdown = $this->fillter_dropdown($validatedData['table_name'],$validatedData['module_id']);


            $columns_list =   Schema::getColumnListing($validatedData['table_name']);

            $table_data_quary = DB::table($validatedData['table_name']);
            $table_data_quary->where('del', '!=' , '1');
            $table_data_quary->orderBy('id', 'DESC');

            if($request->filled('filter'))
            {
                $table_data_quary = $this->sectionDataFillter($request->filter,$table_data_quary);        
            }

            $total_records = $table_data_quary->count();
            $table_data_quary->skip($validatedData['from'])->take(10);
            
            $table_data = $table_data_quary->get();
            
            $data = [];

            for($i=0;$i<count($table_data); $i++)
            {
                foreach($table_data[$i] as $key => $value)
                {
                    
                    $meta = DB::table('module_mata')
                    ->where('module_id',$validatedData['module_id'])
                    ->where('option',$key)
                    ->first();
    
                    if($meta && in_array($meta->type, ['checkbox','radio','dropdown']) && $meta->import_option == '0')
                    {   
                        $where[$meta->dependency_values] = $value;

                        if($meta->dependency == "users" && $meta->dependency_values != "id")
                        {        
                            $role_id =  explode(".",$meta->dependency_values)[1];
                            $where = [
                                'role_id' => $role_id
                            ];
                        }
                        
                        $table_result =  DB::table($meta->dependency)->where($where)->select($meta->dependency_options)->first();
                        $table_result = get_object_vars($table_result);
                        $data[$i][$key] = $table_result[$meta->dependency_options];
                    }
                    else
                    {
                        $data[$i][$key] = $value; 
                    }
    
    
                    
    
                }


            }

             

            $not_allowed = [ 
                    'id',
                    'module__id',
                    'hide__or__show',
                    'user__id',
                    'show__to',
                    '_Verify_',
                    'del'
            ];

            $columns = [];

        for($i=0; $i < count($columns_list); $i++)
        {
            if(!in_array($columns_list[$i], $not_allowed))
            {
                $columns[] = $columns_list[$i];
            }
        }
        
            return view('Admin.Modules.ajex_table', ['request_filter' => $request->filter,'fillter_dropdown' => $fillter_dropdown, 'total_records' => $total_records, 'from' => $validatedData['from'], 'module_id' => $validatedData['module_id'], 'table_name' => $validatedData['table_name'],'table_columns' => $columns,'table_data' => json_decode(json_encode($data), true)]);

        }



    }

    public function sectionDataFatchForWeb(Request $request){
   
        $validator = Validator::make($request->all(), [
            'table_name' => 'required',
            'module_id' => 'required',
            'from' => 'required',
            'fillter' => 'nullable',
            'user_id' => 'required',
            'hide_or_show' => 'required',
        ]);
        
        if ($validator->fails()) {

            $errors = $validator->errors();

            return response()->json([
                'message' => 'Validation Failed',
                'data' => $errors
            ],422);

 
        } else {


            $validatedData =  $validator->valid();

            $fillter_dropdown = $this->fillter_dropdown($validatedData['table_name'],$validatedData['module_id']);

            $columns_list =   Schema::getColumnListing($validatedData['table_name']);

            $table_data_quary = DB::table($validatedData['table_name']);
            $table_data_quary->where('del', '!=' , '1');
            $table_data_quary->orderBy('id', 'DESC');
            $table_data_quary->where('user__id', $validatedData['user_id']);
            $table_data_quary->where('hide__or__show', $validatedData['hide_or_show']);

            if($request->filled('filter'))
            {
                $table_data_quary = $this->sectionDataFillter($request->filter,$table_data_quary);        
            }

            $total_records = $table_data_quary->count();
            $table_data_quary->skip($validatedData['from'])->take(10);
            $table_data = $table_data_quary->get();

            $data = [];


            for($i=0;$i<count($table_data); $i++)
            {
                foreach($table_data[$i] as $key => $value)
                {
                    
                    $meta = DB::table('module_mata')
                    ->where('module_id',$validatedData['module_id'])
                    ->where('option',$key)
                    ->first();
    
                    if($meta && in_array($meta->type, ['checkbox','radio','dropdown']) && $meta->import_option == '0')
                    {   
                        $where[$meta->dependency_values] = $value;

                        if($meta->dependency == "users" && $meta->dependency_values != "id")
                        {        
                            $role_id =  explode(".",$meta->dependency_values)[1];
                            $where = [
                                'role_id' => $role_id
                            ];
                        }
                        
                        $table_result =  DB::table($meta->dependency)->where($where)->select($meta->dependency_options)->first();
                        $table_result = get_object_vars($table_result);
                        $data[$i][$key] = $table_result[$meta->dependency_options];
                    }
                    else
                    {
                        $data[$i][$key] = $value; 
                    }
    
    
                    
    
                }


            }

             

            $not_allowed = [ 
                    'id',
                    'module__id',
                    'user__id',
                    'show__to',
                    'del'
            ];

            $columns = [];

        for($i=0; $i < count($columns_list); $i++)
        {
            if(!in_array($columns_list[$i], $not_allowed))
            {
                $columns[] = $columns_list[$i];
            }
        }
        
            return view('Admin.Modules.ajex_table_for_web_portal', ['request_filter' => $request->filter,'fillter_dropdown' => $fillter_dropdown, 'total_records' => $total_records, 'from' => $validatedData['from'], 'module_id' => $validatedData['module_id'], 'table_name' => $validatedData['table_name'],'table_columns' => $columns,'table_data' => json_decode(json_encode($data), true)]);

        }



    }


    public function sectionDataFatchForWebForDoctor(Request $request){
   
        $validator = Validator::make($request->all(), [
            'table_name' => 'required',
            'module_id' => 'required',
            'from' => 'required',
            'fillter' => 'nullable',
            'user_id' => 'required',
            'hide_or_show' => 'required',
        ]);
        
        if ($validator->fails()) {

            $errors = $validator->errors();

            return response()->json([
                'message' => 'Validation Failed',
                'data' => $errors
            ],422);

 
        } else {


            $validatedData =  $validator->valid();

            $fillter_dropdown = $this->fillter_dropdown($validatedData['table_name'],$validatedData['module_id']);

            $columns_list =   Schema::getColumnListing($validatedData['table_name']);

            $table_data_quary = DB::table($validatedData['table_name']);
            $table_data_quary->where('del', '!=' , '1');
            $table_data_quary->orderBy('id', 'DESC');
            $table_data_quary->where('user__id', $validatedData['user_id']);
            //$table_data_quary->where('hide__or__show', $validatedData['hide_or_show']);

            if($request->filled('filter'))
            {
                $table_data_quary = $this->sectionDataFillter($request->filter,$table_data_quary);        
            }

            $total_records = $table_data_quary->count();
            $table_data_quary->skip($validatedData['from'])->take(10);
            $table_data = $table_data_quary->get();

            $data = [];


            for($i=0;$i<count($table_data); $i++)
            {
                foreach($table_data[$i] as $key => $value)
                {
                    
                    $meta = DB::table('module_mata')
                    ->where('module_id',$validatedData['module_id'])
                    ->where('option',$key)
                    ->first();
    
                    if($meta && in_array($meta->type, ['checkbox','radio','dropdown']) && $meta->import_option == '0')
                    {   
                        $where[$meta->dependency_values] = $value;

                        if($meta->dependency == "users" && $meta->dependency_values != "id")
                        {        
                            $role_id =  explode(".",$meta->dependency_values)[1];
                            $where = [
                                'role_id' => $role_id
                            ];
                        }
                        
                        $table_result =  DB::table($meta->dependency)->where($where)->select($meta->dependency_options)->first();
                        $table_result = get_object_vars($table_result);
                        $data[$i][$key] = $table_result[$meta->dependency_options];
                    }
                    else
                    {
                        $data[$i][$key] = $value; 
                    }
    
    
                    
    
                }


            }

             

            $not_allowed = [ 
                    'id',
                    'module__id',
                    'user__id',
                    'show__to',
                    'del'
            ];

            $columns = [];

        for($i=0; $i < count($columns_list); $i++)
        {
            if(!in_array($columns_list[$i], $not_allowed))
            {
                $columns[] = $columns_list[$i];
            }
        }
        
            return view('Admin.Modules.ajex_table_for_web_portal_for_doctor', ['request_filter' => $request->filter,'fillter_dropdown' => $fillter_dropdown, 'total_records' => $total_records, 'from' => $validatedData['from'], 'module_id' => $validatedData['module_id'], 'table_name' => $validatedData['table_name'],'table_columns' => $columns,'table_data' => json_decode(json_encode($data), true)]);

        }



    }

    public function uploadCsvStore(Request $request){
    
        $validator = Validator::make($request->all(), [
            'module_id_field' => 'required',
            'table_name_field' => 'required',
            'upload_csv_field' => 'required|mimes:csv,txt'
        ]);
        
        if ($validator->fails()) {

            $errors = $validator->errors();

            return response()->json([
                'message' => 'Validation Failed',
                'data' => $errors
            ],422);


        } else {


            $validatedData =  $validator->valid();

            $columns_list =   Schema::getColumnListing($validatedData['table_name_field']);


            $not_allowed = [ 
                    'id',
                    'module__id',
                    'hide__or__show',
                    'user__id',
                    'show__to',
                    '_Verify_',
                    'del'
            ];

            $columns = [];

            for($i=0; $i < count($columns_list); $i++)
            {
                if(!in_array($columns_list[$i],$not_allowed))
                {
                    $columns[] = $columns_list[$i];
                }
            }




            $file = $request->file('upload_csv_field');
            $path = $file->store('temp');
            $csv_data = [];
            if (($handle = fopen(storage_path('app/' . $path), 'r')) !== false) {
                while (($row  = fgetcsv($handle, 1000, ',')) !== false) {
                    $csv_data[] = $row;
                }

               
                fclose($handle);
            }


            for($i=0; $i < count($columns); $i++)
            {
                if(!in_array($columns[$i],$csv_data[0]))
                {
                    return response()->json([
                        'message' => 'Required "'.$columns[$i].'" column',
                    ],400);
                }
            }



               
            
            $csv_store = [];
            for($i=1;$i<count($csv_data);$i++)
            {
                for($j=0;$j<count($csv_data[0]);$j++)
                {
                    if(in_array($csv_data[0][$j],$columns))
                    {
                        $csv_store[$i-1][$csv_data[0][$j]] = $csv_data[$i][$j];
                    }
                }

                $csv_store[$i-1]['del'] =  '0';
                $csv_store[$i-1]['show__to'] =  '';
                $csv_store[$i-1]['user__id'] =  '';
                $csv_store[$i-1]['module__id'] =  '';
                $csv_store[$i-1]['hide__or__show'] =  '1';
                $csv_store[$i-1]['_Verify_'] =  '0';
            }

            DB::table($validatedData['table_name_field'])->insert($csv_store);

            unlink(storage_path('app/' . $path));



            return response()->json([
                'message' => 'Data uploaded successfully',
            ],200);

        } 


    }

    public function getPatientHealthSummary(Request $request)
    {

        $validator = Validator::make($request->all(),[
            'user__id' => 'required',
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

            $medication = DB::table('p_medication')
            ->join('a_medicine', 'p_medication.medication', '=', 'a_medicine.id')
            ->select('a_medicine.name as medication')
            ->where('p_medication.del', '!=', '1')
            ->where('p_medication.user__id', $validatedData['user__id'])
            ->pluck('medication')
            ->implode(' *');

            $disorders = DB::table('p_disorders')
            ->join('a_disorders', 'p_disorders.disorder', '=', 'a_disorders.id')
            ->select('a_disorders.name as disorder')
            ->where('p_disorders.del', '!=', '1')
            ->where('p_disorders.user__id', $validatedData['user__id'])
            ->pluck('disorder')
            ->implode(' *');


            $adverse_effects = DB::table('p_adverse_effects')
            ->join('a_adverse_effects', 'p_adverse_effects.adverse_effect', '=', 'a_adverse_effects.id')
            ->select('a_adverse_effects.name as adverse_effect')
            ->where('p_adverse_effects.del', '!=', '1')
            ->where('p_adverse_effects.user__id', $validatedData['user__id'])
            ->pluck('adverse_effect')
            ->implode(' *');


            $health_summary['medication'] = $medication == '' ? '' : ' *'.$medication;
            $health_summary['disorders'] = $disorders == '' ? '' : ' *'.$disorders;
            $health_summary['adverse_effects'] = $adverse_effects == '' ? '' : ' *'.$adverse_effects;
            
            $data['health_summary'] = $health_summary;

            $meta_data['medication'] = DB::table('module_manager')->where('id',35)->select('id','name','table_name','module_icon','module_header_icon')->first();
            $meta_data['disorders'] = DB::table('module_manager')->where('id',36)->select('id','name','table_name','module_icon','module_header_icon')->first();
            $meta_data['adverse_effects'] = DB::table('module_manager')->where('id',37)->select('id','name','table_name','module_icon','module_header_icon')->first();

            $data['health_summary_meta_data'] = $meta_data;



            $request->merge(['user_id' => $validatedData['user__id']]);
            $socialHistoryController = new SocialHistoryController();
            $fieldsResponse = $socialHistoryController->GetFields($request);
            $socialHistoryForm = $fieldsResponse->getData()->data;

            $data['social_history_form'] = $socialHistoryForm;  
            $data['base_url'] = url('/');


            return response()->json([
                'message' => 'get patient health summary successfully.',
                'data' =>  $data 
            ],200);


        }
    }



    public function searchDropdown(Request $request)
    {
      
        $validator = Validator::make($request->all(),[
            'dependency' => 'required',
            'dependency_options' => 'required',
            'dependency_values' => 'required',
            'search' => 'required',
            'from' => 'required',
        ]);
        
        if ($validator->fails()) {

            $errors = $validator->errors();

            return response()->json([
                'message' => 'Validation Failed',
                'data' => $errors
            ],422);

        } else {

            $validatedData =  $validator->valid();


           

            if($validatedData['dependency'] == 'users')
            {
                $table_data_quary =  DB::table($validatedData['dependency']);
                $table_data_quary->where('del', '!=' , '1');
                $table_data_quary->where($validatedData['dependency_options'], 'LIKE', "%".$validatedData['search']."%");

                $where = [
                    'verified' =>'1',
                    'active' =>'1',
                    'del' => '0'
                   ];

                if($validatedData['dependency_values'] == "id")
                {
                    $table_data_quary->where($where)->where('role_id', '!=' , '1');                   
                }
                else
                {
                    $role_id =  explode(".",$validatedData['dependency_values'])[1];
                    $where = [
                        'role_id' =>$role_id
                    ];

                    $table_data_quary->where($where);
                }


                $total_records = $table_data_quary->count();
                $table_data_quary->skip($validatedData['from'])->take(10);
                $table_data_quary->orderBy($validatedData['dependency_options']);
                $table_data_quary->select('id',$validatedData['dependency_options']);
                $table_data =  $table_data_quary->get();
                
            }
            else
            {
                $table_data_quary =  DB::table($validatedData['dependency']);
                $table_data_quary->where('del', '!=' , '1');
                $table_data_quary->where($validatedData['dependency_options'], 'LIKE', "%".$validatedData['search']."%");
                $total_records = $table_data_quary->count();
                $table_data_quary->skip($validatedData['from'])->take(10);
                $table_data_quary->orderBy($validatedData['dependency_options']);
                $table_data_quary->select('id',$validatedData['dependency_options']);
                $table_data =  $table_data_quary->get();
            
            }
         
            $data = [];
            $i = 0;
            $arr = [];
            foreach($table_data as $tb){
                $arr = [];
                $arr  = (array) $tb;
                $data[$i]['value'] = $arr['id'];
                $data[$i]['label'] = $arr[$validatedData['dependency_options']];
                $i++;
            }

            return response()->json([
                'message' => '',
                'data' =>  $data,
                'count' => $total_records
            ],200);
    
           
        }
    }
    
}