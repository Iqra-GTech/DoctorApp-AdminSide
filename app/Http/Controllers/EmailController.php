<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
    public function SendInstantEmail(array $params)
    {
        try {
            \Mail::send('Admin.Emails.'.$params['blade'], $params['data'], function ($message) use ($params) {
                $message->to($params['to']);
                $message->from(env('MAIL_FROM_ADDRESS'));
                $message->subject($params['subject']);
            });

            return true;
        } catch (\Exception $e) {
            return $e;
        }
    }
}
