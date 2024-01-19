<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Role;
class Permissions extends Model
{
    use HasFactory;

    protected $fillable = ['title']; // Add 'title' to the fillable array
    protected $table = 'permission';

    public function role() {
        return $this->belongsTo(Role::class);
    }


    // public function subtitles()
    // {
    //     return $this->hasMany(Subtitle::class);
    // }

//     public function subpermissions() {
//         return $this->hasMany(SubPermissions::class);
//     }

//     public function subtitles()
// {
//     return $this->hasMany(Subtitle::class);
// }
}
