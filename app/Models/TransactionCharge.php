<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionCharge extends Model
{
    protected $table = 'transaction_charge';
    protected $guarded = ['id'];
    public $timestamps = false;

    public function user(){
        return $this->belongsTo('App\User','user_id');
    }
}
