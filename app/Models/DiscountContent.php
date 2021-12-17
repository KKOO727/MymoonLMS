<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiscountContent extends Model
{
    protected $table = 'discount';
    public $timestamps = false;
    protected $guarded = ['id'];

    public function content(){
        return $this->belongsTo('App\Models\Content','off_id');
    }
    public function category(){
        return $this->belongsTo('App\Models\ContentCategory','off_id');
    }
}
