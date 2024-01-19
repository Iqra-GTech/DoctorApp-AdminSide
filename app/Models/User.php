<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\ModuleManager;
use App\Models\UserMeta;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
            'role_id',
            'email',
            'password',
            'phone_number',
            'created_by',
            'active'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'verified_at' => 'datetime',
    ];


    // public function createdBy()
    // {
    //     return $this->hasOne(User::class, 'id', 'created_by');        
    // }

    public function role()
    {
        return $this->hasOne(Role::class,'id','role_id');
    }

    public function userMeta()
    {
        return $this->hasMany(UserMeta::class,'user_id','id');
    }


    public function ModuleManager()
    {
        return $this->hasMany(ModuleManager::class,'role_id','role_id');
    }




}
