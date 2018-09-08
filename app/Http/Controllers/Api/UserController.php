<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use \App\User;

class UserController extends \App\Http\Controllers\Controller
{
    public function getProfile(Request $request)
    {
        $sApiToken = $this->getParams($request, 'api_token');
//        $objRet = Auth::guard('api')->validate(['api_token' => $sApiToken]);
        $objUser = User::where('api_token', '=', $sApiToken)->firstOrFail();
        $arrRet = \App\lib\common::getApiRet();
        $arrRet['profile'] = User::checkToken($sApiToken)->toArray();
        return response()->json($arrRet);
    }

    /**
     * 登入功能实现
     * @author wf
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $uname = $this->getParams($request, 'uname');
        $pass = $this->getParams($request, 'pass');
        $objUser = User::where('name', '=', $uname)->firstOrFail();
        $arrRet = \App\lib\common::getApiRet();
        $arrRet['api_token'] = $objUser->api_token;
        return response()->json($arrRet);
    }

    /**
     * 保存用户资料
     * @author wf
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveProfile(Request $request)
    {
        $speed = $this->getParams($request, 'speed');
        $quest_len = $this->getParams($request, 'quest_len');
        $arrRet = \App\lib\common::getApiRet();
        $sApiToken = $request->api_token;
        $objUser =  User::checkToken($sApiToken);
        $objUser->speed = $speed;
        $objUser->quest_len = $quest_len;
        $objUser->save();
        $arrRet['profile'] = $objUser->toArray();
        return response()->json($arrRet);
    }

    public function register(Request $request)
    {
        $uname = $this->getParams($request, 'uname');
        $pass = $this->getParams($request, 'pass');
        $objUser = User::where('name', '=', $uname)->first();
        if (!$pass)
        {
            throw new \Error("密码不能为空");
        }
        if ($objUser)
        {
            throw new \Exception('账户已存在');
        }
        else
        {
            $objUser = \App\User::create([
                'name' => $uname,
                'password' => $pass,
                'email' => '',
                'api_token' => str_random(16),
            ]);
        }
        $arrRet = \App\lib\common::getApiRet();
        $arrRet['api_token'] = $objUser->api_token;
        return response()->json($arrRet);
    }
}
