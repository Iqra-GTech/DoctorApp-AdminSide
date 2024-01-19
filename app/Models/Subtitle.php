<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Permissions;

class Subtitle extends Model
{
    use HasFactory;
    protected $fillable = ['text'];

    public function permission()
    {
        return $this->belongsTo(Permissions::class);
    }
}
