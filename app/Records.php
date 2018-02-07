<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Records extends Model
{
    protected $table = 'user_product';
    //主鍵
    protected $primaryKey = 'id';

    //關聯record
    public function record()
    {
    return $this->hasMany(ProductIndex::class,'pid','pid');
    }
  
}