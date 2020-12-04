<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Model
{
    protected $table = 'users';
    
    use SoftDeletes;

 
    public function files()
    {
        return $this->hasMany('App\UserFile');
    }
 
}
