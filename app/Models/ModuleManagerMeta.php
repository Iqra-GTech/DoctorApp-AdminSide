<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModuleManagerMeta extends Model
{
    use HasFactory;

    protected $table = 'module_mata';

    protected $fillable = [
                'module_id',
                'type',
                'option',
                'value',
                'required',
                'dependency',
                'import_option'
    ];



}
