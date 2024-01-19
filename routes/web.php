<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ModuleManagerController;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\PermissionsController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


// Route::get('/clear-cache', function() {
//     Artisan::call('cache:clear');
//     Artisan::call('view:clear');
//     Artisan::call('config:clear');
//     Artisan::call('route:clear');
//     return redirect('/?cache=cleared');
// });

Route::get('/Not-Allowed', function () { return view('Admin.Errors.not_allowed'); });
Route::get('/Back',function(){return redirect()->back();});
Route::middleware(['dashboard_auth'])->group(function () {
    Route::get('/dashboard', function () {return view('Admin.dashboard');})->name('Admin.dashboard')->middleware('canPermission:View Dashboard');
    Route::get('/users/list', function () { return view('Admin.Users.list'); })->name('Admin.users.list')->middleware('canPermission:view users');
    Route::get('/roles/list', function () { return view('Admin.Roles.list'); })->name('Admin.roles.list')->middleware('canPermission:View Roles');
    Route::get('/roles/permissions', [PermissionsController::class,'showPermissions']);
    // Route::post('/roles/permissions/{role_id}', [PermissionsController::class,'update'])->name('roles.permissions.update');
    Route::get('/modules/list', function () { return view('Admin.Modules.list'); })->name('Admin.modules.list')->middleware('canPermission:View modules');
    Route::get('/modules/create', function () { return view('Admin.Modules.create'); })->name('Admin.modules.create')->middleware('canPermission:Create module');
    Route::get('/modules/edit/{module_id}',[ModuleManagerController::class, 'edit'])->name('Admin.modules.edit')->middleware('canPermission:Edit module');
    Route::get('/general-settings/pages/list', function () { return view('Admin.Pages.list'); })->name('Admin.general_settings.pages.list')->middleware('canPermission:View pages');
    Route::get('/general-settings/pages/create',function () { return view('Admin.Pages.create'); })->name('Admin.general_settings.pages.create')->middleware('canPermission:Create page');
    Route::post('/general-settings/pages/store',[PagesController::class, 'Store'])->name('Admin.general_settings.pages.store')->middleware('canPermission:Create page');
    Route::get('/general-settings/pages/edit/{id}',function () { return view('Admin.Pages.edit'); })->name('Admin.general_settings.pages.edit')->middleware('canPermission:Edit page');
    Route::post('/general-settings/pages/update',[PagesController::class, 'update'])->name('Admin.general_settings.pages.update')->middleware('canPermission:Edit page');
    Route::get('/general-settings/supports/list', function () { return view('Admin.Support.list'); })->name('Admin.supports.list')->middleware('canPermission:View App support');
    Route::get('/general-settings/request-updates/list', function () { return view('Admin.RequestUpdate.list'); })->name('Admin.requestUpdate.list')->middleware('canPermission:View Requests');
    Route::get('/sponsers/list', function () { return view('Admin.Sponsers.list'); })->name('Admin.sponsers.list')->middleware('canPermission:View sponsers');
});


Route::get('/', function(){

    if(Auth::user() && Auth::user()->verified == 1)
    {
        return redirect('/dashboard');
    }
    else
    {
        return view('Admin.Auth.sign-in');
    }

});
Route::get('/reset-password', function(){ return view('Admin.Auth.reset_password'); })->name('Admin.resetPassword');
Route::get('/forgot-password', function () { return view('Admin.Auth.forgot-password'); })->name('Admin.forgotPassword');
Route::get('/account-verification', function () { return view('Admin.Auth.account-verification'); })->name('Admin.accountVerification');
