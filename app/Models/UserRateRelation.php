<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserRateRelation extends Model
{
    protected $table = 'user_rate_relation';
    public $timestamps = false;
    protected $guarded = ['id'];

    public static function updateOrNew($user_id,$data){
        foreach ($data as $val){
            UserRateRelation::updateOrCreate(
                ['user_id'=>$user_id,'rate_id'=>$val],
                ['rate_id'=>$val]
            );
        }
    }

    public function rate(){
        return $this->belongsTo('App\Models\UserRate','rate_id');
    }
}
