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

    protected $hidden = ['password', 'remember_token'];

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
    //需要被轉換成日期的屬性。
    protected $dates = ['deleted_at'];

    //取得所有被賦予該標籤。
    public function WebLocation()
    {
        return $this->morphToMany('App\WebLocation', 'sales');
    }
}
