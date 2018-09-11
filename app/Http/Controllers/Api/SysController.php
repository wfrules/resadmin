<?php

namespace App\Http\Controllers\Api;


use Illuminate\Http\Request;
use Log;

class SysController extends \App\Http\Controllers\Controller
{
    public function agent(Request $request){
        $ip = $_SERVER['REMOTE_ADDR'];
        $agent = $_SERVER['HTTP_USER_AGENT'];
        setcookie('wf', 123);
        print_r($_COOKIE);
        $sMsg = "\n\r访问ip:" . $ip . " 代理:" . $agent . "\n\r" ;
        echo($sMsg);
        Log::info($sMsg);
    }

    public function info(Request $request)
    {
        phpinfo();
    }
}
