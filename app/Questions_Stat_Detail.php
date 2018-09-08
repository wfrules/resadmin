<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Questions_Stat_Detail extends Model
{
    protected $table = 'questions_stat_detail';
    protected $fillable = [
        'created_at', 'questions_stat_id', 'note',  'right', 'wrong','users_id'
    ];
}
