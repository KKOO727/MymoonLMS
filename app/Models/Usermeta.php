<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Usermeta extends Model
{
    protected $table = 'users_meta';
    protected $guarded = ['id'];
    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public static function updateOrNew($user_id, $data)
    {
        foreach ($data as $key => $val) {
            if(is_array($val)){
                $val=implode(',',$val);
            }
            Usermeta::updateOrCreate(
                ['user_id' => $user_id, 'option' => $key],
                ['value' => $val]
            );
        }
    }
}
