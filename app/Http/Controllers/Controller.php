<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use DB;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    
    public function save_notifications($title,$short_desc,$sender,$receiver,$type){
        
        
        DB::table('notifications')->insert([
        'title' =>  $title,
        'short_desc' =>  $short_desc,
        'sender' =>  $sender,
        'receiver' =>  $receiver,
        'type' =>  $type
        ]);
                    
                    
    }

}
