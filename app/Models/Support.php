<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Support extends Model
{

    use HasFactory;

    protected $table = 'supports';

    protected $fillable = [
        'title',
        'discription',
        'resolved',
        'date',
        'user_id',
        'images'
    ];


    public function User()
    {
        return $this->hasOne(User::class, 'id', 'user_id');        
    }




}
