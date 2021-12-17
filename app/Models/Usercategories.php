<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Usercategories extends Model
{
    protected $table = "users_category";
    public $timestamps = false;
    protected $guarded = ['id'];

    public function users()
    {
        return $this->hasMany('App\User','category_id');
    }
}
