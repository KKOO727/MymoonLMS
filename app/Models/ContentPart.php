<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContentPart extends Model
{
    protected $table = 'contents_part';
    public $timestamps = false;
    protected $guarded = ['id'];

    public function content(){
        return $this->belongsTo('App\Models\Content','content_id');
    }
}
