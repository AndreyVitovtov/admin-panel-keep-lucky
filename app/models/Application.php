<?php

namespace App\Models;

class Application extends Model
{
	protected $table = 'applications';
	protected $fields = [
		'title', 'url', 'token'
	];
}