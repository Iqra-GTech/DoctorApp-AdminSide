<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Permissions;

class Role extends Model
{

    use HasFactory;

    protected $table = 'roles';

    protected $fillable = [
        'name' => 'Admin'
    ];

    public function permissions() {
        return $this->hasMany(Permissions::class);
    }



}
