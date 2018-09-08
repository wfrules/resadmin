<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Questions_Stat extends Model
{
    protected $table = 'questions_stat';
    protected $fillable = [
        'created_at', 'combo', 'played', 'duration', 'right', 'wrong', 'users_id', 'stat_date'
    ];

    public function details()
    {
        return $this->hasMany('App\Questions_Stat_Detail', 'questions_stat_id', 'id');
    }

    public static function statDaily($user, $aDate)
    {
        $qryParams = ['conditions' => "created_at between '$aDate' and '$aDate 23:59:59'"];
        $arrRet = Question::getSum($user, $qryParams);
        if (count($arrRet['pool_result'])) {//有明细的才要重算
            $objQuestionStat = self::updateOrCreate(['users_id' => $user->id, 'stat_date' => $aDate],
                [
                    'combo' => $arrRet['combo'],
                    'played' => $arrRet['avg_played'],
                    'duration' => $arrRet['avg_duration'],
                    'correct' => $arrRet['right'],
                    'wrong' => $arrRet['wrong'],
                ]
            );
            $objQuestionStat->details()->delete();
            for ($i = 0; $i < count($arrRet['pool_result']); $i++) {
                $objQuestionStatDetail = Questions_Stat_Detail::create([
                    'questions_stat_id' => $objQuestionStat->id,
                    'note' => $arrRet['pool_result'][$i]['note'],
                    'correct' => $arrRet['pool_result'][$i]['right'],
                    'wrong' => $arrRet['pool_result'][$i]['wrong'],
                    'users_id' => $user->id,
                ]);
            }
        }
    }

    public static function getSum($user, $qryParams)
    {
        $arrRet = \App\lib\common::getApiRet();
        $sSql = "SELECT MAX(combo) AS combo,AVG(played) AS played,AVG(duration) AS duration,"
            . "sum(correct) AS correct,sum(wrong) AS wrong FROM questions_stat WHERE users_id=" . $user->id;
        if (isset($qryParams['today']) && ($qryParams['today'])) {
            $sToday = date('Y-m-d', time());
            $sSql .= " and stat_date BETWEEN '$sToday' AND '$sToday 23:59:59'";
        }
        $sSql .= " group by users_id";
        $arrQuery = \DB::select($sSql);
        if(count($arrQuery))
        {
            $objQuery = $arrQuery[0];
            $iCorrect = $objQuery->correct;
            $arrRet['avg_duration'] = ($iCorrect) ? ceil($objQuery->duration) : 0;
            $arrRet['avg_played'] = ($iCorrect) ? ceil($objQuery->played) : 0;
            $arrRet['right'] = $iCorrect;
            $arrRet['wrong'] = $objQuery->wrong;
            $arrRet['combo'] = $objQuery->combo;
        }
        else
        {
            $arrRet['avg_duration'] = 0;
            $arrRet['avg_played'] = 0;
            $arrRet['right'] = 0;
            $arrRet['wrong'] = 0;
            $arrRet['combo'] = 0;
        }


        $sDetailSql = "SELECT a.note,SUM(a.correct) AS correct,SUM(a.wrong) AS wrong "
            . "FROM questions_stat m,questions_stat_detail a WHERE m.id=a.questions_stat_id "
            . "AND m.users_id=" . $user->id;
        if (isset($qryParams['today']) && ($qryParams['today'])) {
            $sToday = date('Y-m-d', time());
            $sDetailSql .= " and m.stat_date BETWEEN '$sToday' AND '$sToday 23:59:59'";
        }
        $sDetailSql .= "  GROUP BY a.note";
        $arrQuery = \DB::select($sDetailSql);
        $arrPool = [];
        for ($i = 0; $i < count(\App\lib\common::Pool); $i++) {
            $iRight = 0;
            $iWrong = 0;
            for ($j = 0; $j < count($arrQuery); $j++) {
                if ($arrQuery[$j]->note == \App\lib\common::Pool[$i]['note']) {
                    $iRight = $arrQuery[$j]->correct;
                    $iWrong = $arrQuery[$j]->wrong;
                    break;
                }
            }
            array_push($arrPool, [
                'caption' => \App\lib\common::Pool[$i]['caption'],
                'code' => \App\lib\common::Pool[$i]['code'],
                'note' => \App\lib\common::Pool[$i]['note'],
                'right' => $iRight,
                'wrong' => $iWrong,
            ]);
        }

        $arrRet['pool_result'] = $arrPool;
        return $arrRet;
    }

}
