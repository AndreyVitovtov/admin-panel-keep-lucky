<?php

namespace App\Models;

class Apk extends Model
{
	protected $table = 'apks';
	protected $fields = [
		'title', 'url'
	];
}