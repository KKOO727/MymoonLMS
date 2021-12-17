<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContentMeta extends Model
{
    protected $table = 'contents_meta';
    public $timestamps = false;
    protected $guarded = ['id'];

    public function content(){
        return $this->belongsTo('App\Models\Content','content_id');
    }

    public static function updateOrNew($content_id,$data){
        foreach ($data as $key => $val){
            if(is_array($val)){
                $val=implode(',',$val);
            }
            ContentMeta::updateOrCreate(
                ['content_id'=>$content_id,'option'=>$key],
                ['value'=>$val]
            );
        }
    }
}
