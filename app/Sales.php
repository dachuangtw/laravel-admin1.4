<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sales extends Model
{
    //使用軟刪除
    use SoftDeletes;
    //業務資料表
    protected $table = 'sales';
    //主鍵
    protected $primaryKey = 'sid';

    //業務店鋪據點(用|分隔)
        public function getStoreLocationAttribute($store_location)
    {
        if (is_string($store_location)) {
            return explode('|',$store_location);
        }

        return $store_location;
    }

    public function setStoreLocationAttribute($store_location)
    {
        if (is_array($store_location)) {
            $this->attributes['store_location'] = implode('|',$store_location);
        }
    }
}
