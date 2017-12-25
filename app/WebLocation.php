<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WebLocation extends Model
{
    //店鋪據點資料表
    protected $table = 'web_location';
    //主鍵
    protected $primaryKey = 'id';

    //業務(用|分隔)
    public function getSalesAttribute($sales)
    {
        if (is_string($sales)) {
            return explode('|',$sales);
        }

        return $sales;
    }

    public function setSalesAttribute($sales)
    {
        if (is_array($sales)) {
            $this->attributes['sales'] = implode('|',$sales);
        }
    }

}
