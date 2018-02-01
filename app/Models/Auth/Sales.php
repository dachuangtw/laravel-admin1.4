<?php

namespace App\Models\Auth;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Sales extends Authenticatable
{
    protected $table = 'sales';

	protected $primaryKey = 'sid';

	protected $fillable = [
		'sales_name',
		'sales_id',
		'nickname',
		'password',
	];

	protected $hidden = [
        'password', 'remember_token',
    ];
}
