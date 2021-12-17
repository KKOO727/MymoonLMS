<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;
    protected $table = 'users';
    protected $guarded = ['id'];
    public $timestamps = false;
    //public $fillable = ['id','username','name','email','email_verified_at','password','type','mode'];

    public function usermetas()
    {
        return $this->hasMany('App\Models\Usermeta');
    }

    public function category()
    {
        return $this->belongsTo('App\Models\Usercategories', 'category_id');
    }

    public function contents()
    {
        return $this->hasMany('App\Models\Content');
    }

    public function sells()
    {
        return $this->hasMany('App\Models\Sell','seller_id');
    }

    public function buys()
    {
        return $this->hasMany('App\Models\Sell', 'buyer_id');
    }

    public function channels()
    {
        return $this->hasMany('App\Models\Channel', 'user_id');
    }

    public function point()
    {
        return $this->hasMany('App\Models\Point', 'user_id');
    }

    public function preferential()
    {
        return $this->hasMany('App\Models\Point', 'preferential_id');
    }

    public function follow()
    {
        return $this->hasMany('App\Models\Follower', 'user_id');
    }

    public function support()
    {
        return $this->hasMany('App\Models\ContentSupport', 'sender_id');
    }

    public function supporter()
    {
        return $this->hasMany('App\Models\ContentSupport', 'supporter_id');
    }

    public function articles()
    {
        return $this->hasMany('App\Models\Article', 'user_id');
    }

    public function quizzes()
    {
        return $this->hasMany('App\Models\Quiz', 'user_id');
    }

    public function isAdmin()
    {
        if ($this->type == 'admin') {
            return true;
        }
        return false;
    }

}
