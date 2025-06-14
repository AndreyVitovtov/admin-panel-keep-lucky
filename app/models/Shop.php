<?php

namespace App\Models;

class Shop extends Model
{
	protected $table = 'shops';
	protected $fields = [
		'title', 'url', 'token'
	];
}