<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $guarded = ['id'];
    protected $table = 'transaction';
    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo('App\User','user_id');
    }
    public function buyer()
    {
        return $this->belongsTo('App\User','buyer_id');
    }
    public function content()
    {
        return $this->belongsTo('App\Models\Content','content_id');
    }
    public function balance()
    {
        return $this->hasOne('App\Models\Balance','id','balance_id');
    }
}
