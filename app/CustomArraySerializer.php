<?php

namespace App;
use Carbon\Carbon;

class CustomArraySerializer extends \League\Fractal\Serializer\ArraySerializer {

	public function item($resourceKey, array $data) {
		if($resourceKey){
			return $data;
		}

		return [ $resourceKey => $data ];
	}

	public function collection($resourceKey, array $data) {
		return $data;
	}
}
