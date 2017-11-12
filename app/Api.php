<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Api extends Model{

	use SoftDeletes;

	protected $dates = ['deleted_at'];

	public function user(){
		return $this->belongsTo('App\User');
	}

}
