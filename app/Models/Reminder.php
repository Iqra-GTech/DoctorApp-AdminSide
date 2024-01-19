<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reminder extends Model
{

    use HasFactory;

    protected $table = 'reminders';

    protected $fillable = [
        'des',
        'date',
        'title',
        'time',
        'unique_id',
        'status',
        'user_id',
        'recursion'
    ];



}
