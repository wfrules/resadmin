<?php
namespace App\lib;

Class common
{
    const Pool = [
        ['caption'=> 1, 'code'=>'C3', 'note'=>48],
        ['caption'=> 2, 'code'=>'D3', 'note'=>50],
        ['caption'=> 3, 'code'=>'E3', 'note'=>52],
        ['caption'=> 4, 'code'=>'F3', 'note'=>53],
        ['caption'=> 5, 'code'=>'G3', 'note'=>55],
        ['caption'=> 6, 'code'=>'A3', 'note'=>57],
        ['caption'=> 7, 'code'=>'B3', 'note'=>59],
        ['caption'=> 'i', 'code'=>'C4', 'note'=>60]
    ];

    public static function getApiRet(){
        return ['status'=>0];
    }

}