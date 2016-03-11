<?php

namespace App;
use Carbon\Carbon;

class UserTransformer extends \League\Fractal\TransformerAbstract {

	/**
	* @param \App\
	*/
	public function transform($user) {
		$result = [];

		$result["id"] = (int)$user->id;
		$result["first_name"] = $user->first_name;
		$result["last_name"] = $user->last_name;
		$result["age"] = (int)$user->id;
		$result["gender"] = (bool)$user->gender;
		$result["nationality"] = strtoupper($user->nationality);

		$createdAt = new Carbon($user->created_at);

		$result["created_at"] = $createdAt->format('Y-m-d');

		return $result;
	}
}