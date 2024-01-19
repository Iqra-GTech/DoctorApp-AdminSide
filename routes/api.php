<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ReminderController;
use App\Http\Controllers\SupportController;
use App\Http\Controllers\SponserController;
use App\Http\Controllers\ModuleManagerController;
use App\Http\Controllers\AuthorizationController;
use App\Http\Controllers\RequestUpdateController;
use App\Http\Controllers\SocialHistoryController;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\PermissionsController;






Route::middleware('auth:sanctum')->group(function () {

Route::post('/permission_update', [PermissionsController::class,'updatePermissions']);

Route::GET('/module-managers/dropdown/search', [ModuleManagerController::class, 'searchDropdown']);

Route::get('/check-Bearer-token', function () { return 0; });
Route::post('/counter-function', [UserController::class, 'counterFunction'])->name('counterFunction');
Route::post('/get-no-of-records', [UserController::class, 'getNoOfRecords'])->name('getNoOfRecords');


Route::post('/doctor/my-patient', [UserController::class, 'myPatient'])->name('myPatient');
Route::post('/doctor/my-patient-all', [UserController::class, 'myPatientAll'])->name('myPatientAll');


Route::post('/social-history/get-fields', [SocialHistoryController::class, 'GetFields']);
Route::post('/social-history/store-or-update-fields', [SocialHistoryController::class, 'StoreOrUpdateFields']);

Route::post('/users/request-to-receiver-store', [UserController::class, 'RequestToReceiverStore'])->name('user.requestToReceiverStore');
Route::post('/users/show-all-requests-to-friend-and-family', [UserController::class, 'ShowAllRequestsToFriendAndFamily'])->name('user.showAllRequestsToFriendAndFamily');
Route::post('/users/request-status-update-receiver', [UserController::class, 'RequestStatusUpdateReceiver'])->name('user.requestStatusUpdateReceiver');
Route::post('/users/request-status-update-sender', [UserController::class, 'RequestStatusUpdateSender'])->name('user.requestStatusUpdateSender');


Route::GET('/users/request-to-doctor', [UserController::class, 'RequestToDoctor'])->name('user.requestToDoctor');
Route::put('/users/show-all-requests-to-patient', [UserController::class, 'ShowAllRequestsToPatient'])->name('user.showAllRequestsToPatient');
Route::put('/users/show-all-requests-to-doctor', [UserController::class, 'ShowAllRequestsToDoctor'])->name('user.showAllRequestsToDoctor');
Route::put('/users/show-all-requests-to-doctor-reject-by-patient', [UserController::class, 'ShowAllRequestsToDoctorRejectBYPatient'])->name('user.showAllRequestsToDoctorRejectBYPatient');
Route::put('/users/show-all-requests-to-doctor-reject-by-doctor', [UserController::class, 'ShowAllRequestsToDoctorRejectBYDoctor'])->name('user.showAllRequestsToDoctorRejectBYDoctor');

Route::post('/users/request-to-doctor-store', [UserController::class, 'RequestToDoctorStore'])->name('user.requestToDoctorStore');
Route::post('/users/show-requests-to-doctor-by-id', [UserController::class, 'ShowRequestsToDoctorById'])->name('user.showRequestsToDoctorById');
Route::put('/users/request-status-update-doctor', [UserController::class, 'RequestStatusUpdateDoctor'])->name('user.requestStatusUpdateDoctor');
Route::put('/users/request-status-update-patient', [UserController::class, 'RequestStatusUpdatePatient'])->name('user.requestStatusUpdatePatient');
Route::resource('/users', UserController::class);
Route::put('/users/{user}/update-status', [UserController::class, 'updateStatus'])->name('user.updateStatus');
Route::post('/users/change-password', [UserController::class, 'updatePassword'])->name('user.passwordUpdate');
Route::post('/get-general-settings', [UserController::class, 'GeneralSettingsGet'])->name('user.generalSettingsGet');
Route::post('/logout', [AuthorizationController::class, 'logout'])->name('logout');



Route::resource('/roles', RoleController::class);
Route::put('/reminders/{reminder}/update-status', [ReminderController::class, 'updateStatus']);
Route::resource('/reminders', ReminderController::class);
Route::resource('/supports', SupportController::class);
Route::put('/pages/{page}/update-status', [PagesController::class, 'updateStatus'])->name('pages.updateStatus');
Route::resource('/request-updates', RequestUpdateController::class);
Route::post('/module-managers/delete-field', [ModuleManagerController::class, 'deleteField'])->name('deleteField');

Route::post('/module-managers/save-or-update-single-field', [ModuleManagerController::class, 'saveOrUpdateSingleField'])->name('saveOrUpdateSingleField');
Route::post('/module-managers/update-upper-fields', [ModuleManagerController::class, 'updateUpperFields'])->name('updateUpperFields');
Route::post('/module-managers/upload-csv-get', [ModuleManagerController::class, 'uploadCsvGet'])->name('uploadCsvGet');
Route::post('/module-managers/upload-csv-store', [ModuleManagerController::class, 'uploadCsvStore'])->name('uploadCsvStore');



Route::post('/module-managers/comma-separated-values-array-from-linked-table', [ModuleManagerController::class, 'comma_separated_values_array_from_linked_table']);

Route::GET('/module-managers/fields/{id}', [ModuleManagerController::class, 'getModuleManagersFields'])->name('getModuleManagersFields');
Route::GET('/module-managers/get-tables', [ModuleManagerController::class, 'getTables'])->name('getTables');
Route::put('/module-managers/get-columns', [ModuleManagerController::class, 'getColumns'])->name('getColumns');
Route::resource('/module-managers', ModuleManagerController::class);
Route::put('/module-managers/{id}/update-status', [ModuleManagerController::class, 'updateStatus'])->name('moduleManagers.updateStatus');
Route::post('/change-mode', [UserController::class, 'change_mode'])->name('changeMode');
Route::post('/get-notifications', [UserController::class, 'GetNotifications'])->name('GetNotifications');
Route::post('/all-notifications-mark-as-read', [UserController::class, 'AllNotificationsMarkAsRead'])->name('AllNotificationsMarkAsRead');
Route::post('/single-notifications-mark-as-read', [UserController::class, 'SingleNotificationsMarkAsRead'])->name('SingleNotificationsMarkAsRead');
Route::post('/count-notifications', [UserController::class, 'CountNotifications'])->name('CountNotifications');

Route::post('/get-emergency-contacts-fields', [UserController::class, 'getEmergencyContactsfields'])->name('getEmergencyContactsfields');
Route::post('/store-emergency-contacts-fields', [UserController::class, 'storeEmergencyContactsfields'])->name('storeEmergencyContactsfields');
Route::post('/get-emergency-contacts-data', [UserController::class, 'getEmergencyContactsData'])->name('getEmergencyContactsData');
Route::post('/get-patient-health-summary', [ModuleManagerController::class, 'getPatientHealthSummary'])->name('getPatientHealthSummary');

Route::post('/get-profile-fields', [UserController::class, 'getProfilefields'])->name('getProfilefields');
Route::post('/store-profile-fields', [UserController::class, 'storeProfile'])->name('storeProfile');
Route::post('/get-profile-data', [UserController::class, 'getProfileData'])->name('getProfileData');
Route::post('/code-verification', [AuthorizationController::class, 'codeVerification'])->name('users.codeVerification');
Route::post('/section-data/insert', [ModuleManagerController::class, 'sectionDataInsert'])->name('sectionDataInsert');
Route::post('/section-data/fatch', [ModuleManagerController::class, 'sectionDataFatch'])->name('sectionDataFatch');
Route::post('/section-data/edit', [ModuleManagerController::class, 'sectionDataEdit'])->name('sectionDataEdit');
Route::post('/section-data/update', [ModuleManagerController::class, 'sectionDataUpdate'])->name('sectionDataUpdate');
Route::post('/section-data/delete', [ModuleManagerController::class, 'sectionDataDestroy'])->name('sectionDataDestroy');
Route::post('/section-data/hide-or-show', [ModuleManagerController::class, 'sectionDataHideOrShow'])->name('sectionDataHideOrShow');
Route::post('/show-section-data-to-doctor', [ModuleManagerController::class, 'sectionDataShowToDoctor'])->name('sectionDataShowToDoctor');
Route::post('/section-data/fatch-for-web', [ModuleManagerController::class, 'sectionDataFatchForWeb'])->name('sectionDataFatchForWeb');
Route::post('/section-data/fatch-for-web-for-doctor', [ModuleManagerController::class, 'sectionDataFatchForWebForDoctor'])->name('sectionDataFatchForWebForDoctor');
});

Route::post('/login', [AuthorizationController::class, 'login'])->name('login');
Route::post('/register', [AuthorizationController::class, 'register'])->name('users.register');
Route::post('/forget-password-send-otp', [AuthorizationController::class, 'forgetPasswordSendOTP'])->name('forgetPasswordSendOTP');
Route::post('/forget-password-change', [AuthorizationController::class, 'forgetPasswordChange'])->name('forgetPasswordChange');
Route::post('/update-password', [AuthorizationController::class, 'updatePassword'])->name('passwordUpdate');
Route::get('/roles-without-login', [RoleController::class, 'rolesWithoutLogin'])->name('rolesWithoutLogin');
Route::resource('/sponsers', SponserController::class);
Route::resource('/pages', PagesController::class);
