<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'payment_way';
    //主鍵
    protected $primaryKey = 'pay_id';
}
