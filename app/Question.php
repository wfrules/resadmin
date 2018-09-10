<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Mockery\CountValidator\Exception;

class Question extends Model
{
    protected $fillable = [
        'created_at','users_id','speed','quest_at', 'submit_at','played','instrument'
    ];

    public function details()
    {
        return $this->hasMany('App\Questions_Detail', 'questions_id', 'id');
    }


    public static function getSum($user, $qryParams)
    {
        $arrRet = \App\lib\common::getApiRet();
        $iRight = 0;
        $iWrong = 0;
        $objQuery = self::where('users_id', '=', $user->id);
        if (isset($qryParams['conditions']))
        {
            $objQuery->whereRaw($qryParams['conditions']);
        }
        if (isset($qryParams['today']) && ($qryParams['today']))
        {
            $sToday = date('Y-m-d',time());
            $objQuery->whereRaw("quest_at BETWEEN '$sToday' AND '$sToday 23:59:59'");
        }
        $arrQuestions = $objQuery->get();
        $iMaxCombo = 0;
        $iCombo = 0;
        $iPlayedTotal = 0;
        $arrPool = [];
        $iDurationTotal = 0;
        for($i = 0; $i < count(\App\lib\common::Pool);$i++)
        {
            array_push($arrPool, [
                'caption'=>\App\lib\common::Pool[$i]['caption'],
                'code'=>\App\lib\common::Pool[$i]['code'],
                'note'=>\App\lib\common::Pool[$i]['note'],
                'right'=>0,
                'wrong'=>0,
            ]);
        }
        for($i = 0; $i < count($arrQuestions); $i++){
            $objQuestion = $arrQuestions[$i];
            $bQuestionRight = true;
            for ($j = 0; $j < count($objQuestion->details); $j++)
            {
                $objDetail = $objQuestion->details[$j];
                $objPool = null;
                for($k = 0; $k < count($arrPool); $k++)
                {
                    if($arrPool[$k]['note'] == $objDetail->content)
                    {
                        break;
                    }
                }
                if ($objDetail->content != $objDetail->answer)
                {
                    $bQuestionRight = false;
                    $arrPool[$k]['wrong']++;
                }
                else
                {
                    $arrPool[$k]['right']++;
                }
            }
            if ($bQuestionRight)
            {
                $iDurationTotal += floor((strtotime($objQuestion->submit_at)-strtotime($objQuestion->quest_at))%86400%60);
                $iPlayedTotal+= $objQuestion->played;
                $iRight++;
                $iCombo++;
                $iMaxCombo = max($iMaxCombo, $iCombo);
            }
            else
            {
                $iWrong++;
                $iMaxCombo = max($iMaxCombo, $iCombo);
                $iCombo = 0;
            }
        }
        $arrRet['avg_duration'] = ($iRight)?ceil($iDurationTotal/$iRight):0;
        $arrRet['avg_played'] = ($iRight)?ceil($iPlayedTotal/$iRight):0;
        $arrRet['right'] = $iRight;
        $arrRet['wrong'] = $iWrong;
        $arrRet['combo'] = $iMaxCombo;
        $arrRet['pool_result'] = $arrPool;
        return $arrRet;
    }
}
