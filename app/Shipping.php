<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Shipping extends Model
{
    protected $table = 'shipping_way';
    protected $primaryKey = 'ship_id';
}
