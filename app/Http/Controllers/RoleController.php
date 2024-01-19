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



class RoleController extends Controller
{

    public function index(Request $request)
    {
    //   $roles =   Role::where("del", 0)->get();

    $roles_quary = Role::query();
    $roles_quary->where('del','0');

    $recordsFiltered = $recordsTotal = Role::count();

    if($request->filled('filter'))
    {
        [$roles_quary , $recordsFiltered] =  $this->filter($request->filter,$roles_quary,$recordsFiltered);
    }


    $start = $request->input('start', 0);
    $length = $request->input('length', 10);
    $roles_quary->offset($start)->limit($length);
    $roles_quary->orderBy('id', 'desc');
    $roles = $roles_quary->get();

        if($roles)
        {    
                $data =  [
                    'role' => $roles,
                    ];

                return response()->json([
                    'message' => 'Get roles list successfully',
                    'data' => $data,
                    'recordsFiltered' => $recordsFiltered,
                    'recordsTotal' => $recordsTotal,
                    'draw' => intval($request->draw)
                ],200);

        }
        else
        {
            return response()->json([
                'message' => 'Roles not found'
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

    public function rolesWithoutLogin()
    {
      $roles =   DB::table('roles')->where('del', 0)->where('id', '!=', 1)->get();

        if($roles)
        {    
                $data =  [
                    'role' => $roles,
                    ];

                return response()->json([
                    'message' => 'Get roles list successfully',
                    'data' => $data
                ],200);

        }
        else
        {
            return response()->json([
                'message' => 'Roles not found'
            ],400);
        }
    }

    public function create()
    {
        //
    }
    
    public function show($id)
    {

        $role = Role::find($id);

        if($role)
        {

            $data =  [
                'role' => $role,
                ];

                return response()->json([
                    'message' => 'Get role successfully',
                    'data' => $data
                ],200);

        }
        else
        {
            return response()->json([
                'message' => 'Role not found'
            ],400);
        }
        
    }

    public function edit($id)
    {
        $role = Role::find($id);

        if($role)
        {
            $data =  [
                'role' => $role
                ];

                return response()->json([
                    'message' => 'Get role successfully',
                    'data' => $data
                ],200);

        }
        else
        {
            return response()->json([
                'message' => 'Role not found'
            ],400);
        }
        
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required'
        ]);
        
        if ($validator->fails()) {

            $errors = $validator->errors();

            return response()->json([
                'message' => 'Validation Failed',
                'data' => $errors
            ],422);


        } else {
          
           $validatedData =  $validator->valid();

           Role::where('id', $id)->update([
                'name' => $validatedData['name']
            ]);

            return response()->json([
                'message' => 'Role updated successfully'
            ],200);
            
        }        

    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required'
        ]);
        
        if ($validator->fails()) {

            $errors = $validator->errors();

            return response()->json([
                'message' => 'Validation Failed',
                'data' => $errors
            ],422);


        } else {
          
           $validatedData =  $validator->valid();

           $role_id = role::insertGetId([
                'name' => $validatedData['name'],
                'created_at' => now()
            ]);
    
            return response()->json([
                'message' => 'Role register successfully'
            ],200);
            
        }        

    } 

    public function destroy(Role $role)
    {

        Role::where('id', $role->id)->update([
            'del' => '1'
        ]);

        return response()->json([
            'message' => 'Role deleted successfully'
        ],200);


    }




}
