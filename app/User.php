<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract 
{
    use Authenticatable, CanResetPassword;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','image_id','book_id','confirmation_code','confirmed','admin'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function getAuthIdentifierName(){
        return 'id';
    }

    /**
    * Define a many-to-many relationship.
    *
    * @return Illuminate\Database\Eloquent\Relations\hasMany
    */
    public function books()
    {
        return $this->hasMany('App\Book');
    }

    /**
    * Define a many-to-many relationship.
    *
    * @return Illuminate\Database\Eloquent\Relations\hasMany
    */
    public function image()
    {
        return $this->hasOne('App\Image');
    }
}
