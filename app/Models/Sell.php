<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sell extends Model
{

    protected $table = 'sells';
    protected $guarded = ['id'];
    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo('App\User','seller_id');
    }
    public function buyer()
    {
        return $this->belongsTo('App\User','buyer_id');
    }
    public function content()
    {
        return $this->belongsTo('App\Models\Content','content_id');
    }
    public function transaction()
    {
        return $this->belongsTo('App\Models\Transaction','transaction_id');
    }
    public function rates(){
        return $this->hasMany('App\Models\SellRate','sell_id');
    }
    public function rate(){
        return $this->hasOne('App\Models\SellRate','sell_id');
    }
}
