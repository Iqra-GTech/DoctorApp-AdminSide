<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestUpdate extends Model
{

    use HasFactory;

    protected $table = 'request_updates';

    protected $fillable = [
        'title',
        'discription',
        'resolved',
        'date',
        'user_id',
        'module_id'
    ];


    public function User()
    {
        return $this->hasOne(User::class, 'id', 'user_id');        
    }

    public function Module()
    {
        return $this->hasOne(ModuleManager::class, 'id', 'module_id');        
    }




}
