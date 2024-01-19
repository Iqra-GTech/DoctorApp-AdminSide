<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ModuleManager;
use App\Models\UserMeta;
use App\Models\User;
use App\Models\ModuleManagerMeta;
use App\Models\Role;

class ModuleManager extends Model
{
    use HasFactory;

    protected $table = 'module_manager';

    protected $fillable = [
                'name',
                'created_by',
                'active',
                'table_name',
                'role_id'
    ];

    public function ModuleManagerMeta()
    {
        return $this->hasMany(ModuleManagerMeta::class,'module_id','id');
    }

    public function createdBy()
    {
        return $this->hasOne(User::class, 'id', 'created_by');        
    }

    public function role()
    {
        return $this->hasOne(Role::class,'id','role_id');
    }




}
