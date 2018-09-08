<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\User;

class TrainController extends \App\Http\Controllers\Controller
{
    /**
     * 提交答案接口
     * @author wf
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function submit(Request $request)
    {
    	$sContent = $this->getParams($request, 'content');
    	$sAnswer = $this->getParams($request, 'answer');
        $sApiToken = $request->api_token;
        $objUser =   \App\User::checkToken($sApiToken);
        $iUid = $objUser->id;
        \DB::beginTransaction();
        $objQuestion = \App\Question::create([
            'users_id' => $iUid,
            'speed' => $objUser->speed,
            'quest_at'=>  $this->getParams($request, 'quest_at'),
            'submit_at' =>  $this->getParams($request, 'submit_at'),
            'played' =>  $this->getParams($request, 'played'),
        ]);
        $arrContens = explode(',', $sContent);
        $arrAnswers = explode(',', $sAnswer);
        for($i = 0; $i < count($arrContens); $i++)
        {
            \App\Questions_Detail::create([
                'questions_id' => $objQuestion->id,
                'content' => $arrContens[$i],
                'answer' => $arrAnswers[$i],
            ]);
        }
         $arrRet = \App\lib\common::getApiRet();
         $arrRet['qid'] = $objQuestion->id;
        \App\Questions_Stat::statDaily($objUser, date("Y-m-d"));
        \DB::commit();
         return response()->json($arrRet);
    }

    public function reset(Request $request)
    {
        $sApiToken = $request->api_token;
        $objUser =   \App\User::checkToken($sApiToken);    
        $arrQuestions = \App\Question::where('users_id', '=', $objUser->id)->get();
        for ($i = 0; $i < count($arrQuestions); $i++)
        {
            $arrQuestions[$i]->details()->delete();
            $arrQuestions[$i]->delete();
        }
        $arrRet = \App\Questions_Stat::getSum($objUser, []);
        return response()->json($arrRet); 
    }

    public function getSum(Request $request){
        $arrRet = \App\lib\common::getApiRet();
        $sApiToken = $request->api_token;
        $objUser =   \App\User::checkToken($sApiToken);
        $qryParams = $this->getParams($request, 'qryparams');
        $arrRet = \App\Questions_Stat::getSum($objUser, $qryParams);
        return response()->json($arrRet);        
    }

    public function debug()
    {

        \DB::beginTransaction();
        $objUser = User::where('id', '=', 1)->firstOrFail();
        $date =  "2018-09-07";
        \App\Questions_Stat::statDaily($objUser,$date);
        var_dump($objUser->name);
        echo "entered~~~~~~~~~~~~~~~~~~~~~~$date";
        $arrRet = \App\lib\common::getApiRet();
        \DB::commit();
        return response()->json($arrRet);
    }

    /** 获取题库
     * @author wf
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPool(Request $request){
        $arrRet = \App\lib\common::getApiRet();
//        $sApiToken = $request->api_token;
//        $objUser =   \App\User::checkToken($sApiToken);
        $arrPool = \App\lib\common::Pool;
        $arrRet['pool'] = $arrPool;
        return response()->json($arrRet);
    }


    /** 获取题目
     * @author wf
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getQuest(Request $request){
        $arrRet = \App\lib\common::getApiRet();
        $sApiToken = $request->api_token;
        $objUser =   \App\User::checkToken($sApiToken);
        $arrQuest = [];
        for ($i = 0; $i < $objUser->quest_len; $i++) {
            $idx = rand(0, count(\App\lib\common::Pool) - 1);
            array_push($arrQuest, \App\lib\common::Pool[$idx]);
        }
        $arrRet['quest'] = $arrQuest;
        $arrRet['quest_at'] = date('y-m-d h:i:s',time());
        return response()->json($arrRet);
    }

    public function getStat(Request $request){
        $arrRet = \App\lib\common::getApiRet();
        $sApiToken = $request->api_token;
        $objUser =   \App\User::checkToken($sApiToken);
        $arrQuery = \App\Questions_Stat::where('users_id', '=', $objUser->id)->get();
        $arrRet['list'] = $arrQuery;
        return response()->json($arrRet);
    }
}
