<?php

namespace App\Models;

use App\Models\Model;

class AccessesOptions extends Model
{
	protected $table = 'accesses_options';
	protected $fields = [
		'section', 'title'
	];
}