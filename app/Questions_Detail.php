<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Questions_Detail extends Model
{
    protected $table = 'questions_detail';
    protected $fillable = [
        'created_at','content', 'answer','questions_id'
    ];
}
