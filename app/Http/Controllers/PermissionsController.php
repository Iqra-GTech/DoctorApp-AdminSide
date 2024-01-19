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
use App\Models\Permissions;
use Illuminate\Support\Facades\DB as FacadesDB;

class PermissionsController extends Controller
{

    public function showPermissions()
{


    // $permissions = FacadesDB::table('permissions')->get();
    // // $subpermissions = FacadesDB::table('subpermissions')->get();


    // $subpermissions = FacadesDB::table('subpermissions')->get();

    return view('Admin.Roles.permissions');
    // return view('Admin.Roles.permissions', ['permissions' => $permissions]);
}


// public function showSubpermissions($permissionId)
// {
//     $subpermissions =FacadesDB::table('subpermissions')
//         ->where('permission_id', $permissionId)
//         ->get();

//         return view('Admin.Roles.subpermissions', ['subpermissions' => $subpermissions]);
// }

public function create()
{
    return view('Admin.Roles.subpermissions');
}


// public function store(Request $request)
//     {
//         // Validate the request data
//         $request->validate([
//             'title' => 'required|string|max:255', // Add your validation rules
//         ]);

//         // Create a new permission
//         $permission = new Permission;
//         $permission->title = $request->input('title');
//         // Add any other fields you have in your Permission model

//         // Save the permission
//         $permission->save();

//         // Redirect or return a response, e.g., to a permissions index page
//         return response()->json('message', 'Permission created successfully');
//     }



// public function storeSubtitle(Request $request, $titleId)
// {
//     $request->validate([
//         'text' => 'required|string',
//     ]);

//     Subtitle::create([
//         'text' => $request->input('text'),
//         'permission_id' => $titleId,
//     ]);


// }



// public function store(Request $request)
// {
//     $request->validate([
//         'title' => 'required|string',
//         'subtitles' => 'array',
//         'subtitles.*' => 'nullable|string',
//     ]);

//     // Create the permission with the provided title
//     $permission = Permissions::create([
//         'title' => $request->input('title'),
//     ]);

//     // Create associated subtitles
//     if ($request->has('subtitles')) {
//         $subtitles = $request->input('subtitles');
//         foreach ($subtitles as $text) {
//             $permission->subtitles()->create([
//                 'text' => $text,
//             ]);
//         }
//     }

//     return redirect()->route('permissions.create')->with('success', 'Permission created successfully');
// }

// public function update(Request $request, $id)
// {

//     $selectedPermissions = $request->input('permissions');


//     $role = Role::find($id);
//     $role->permissions()->sync($selectedPermissions);


//     return dd('success');
//     return response()->json(['message'=>'done']);
// }


public function updatePermissions(Request $request) {
    // Validate the form data
    $request->validate([
        'role_id' => 'required|exists:roles,id',
        'permission' => 'array',
    ]);

    $role = Role::find($request->input('role_id'));

    if (!$role) {
        return redirect()->back()->with('error', 'Role not found.');
    }

    $permissions = $request->input('permission');

    
    $role->permissions()->delete();


    foreach ($permissions as $permissionName) {
        $permission = new Permissions();
        $permission->name = $permissionName;
        $role->permissions()->save($permission);
    }

    return response()->json(['message' => 'Permissions updated successfully']);
}


public function editRole($roleId) {
    $role = Role::find($roleId);
    $permissions = $role->permissions->pluck('name')->toArray();

    return view('Admin.Roles.permissions', compact('role', 'permissions'));
}



}
