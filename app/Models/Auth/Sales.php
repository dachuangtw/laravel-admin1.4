<?php

namespace App\Models\Auth;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Sales extends Authenticatable
{
	//DB2
	protected $connection = 'mysql2';

    protected $table = 'sales';

	protected $primaryKey = 'sales_id';

	protected $fillable = [
		'wid',
		'name',
		'account',
		'nickname',
		'password',
	];

	protected $hidden = [
        'password', 'token',
    ];

	protected $dates = [
		'created_at',
		'updated_at',
		'deleted_at',
		'collect_at',
		'limited_time',
	];
}
