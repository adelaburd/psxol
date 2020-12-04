<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserFile extends Model
{
    protected $table = 'user_files';

    use SoftDeletes;

    public function user()
    {
        return $this->belongsTo('App\User');
    }
    //public static function setPhoto($foto){


    //}
}
