<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Book extends Model
{
    /**
	 * The database table used by the model.
	 *
	 * @var string
	*/
	protected $table = 'books';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [ 'category','name','author','image_id', 'user_id', 'borowed'];

    /**
	* Define a one-to-many relationship.
	*
	* @return Illuminate\Database\Eloquent\Relations\belongsTo
	*/
	public function user()
	{
		return $this->belongsTo('App\User');
	}

}
