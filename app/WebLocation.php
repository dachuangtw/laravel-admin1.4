<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WebLocation extends Model
{
    //店鋪據點資料表
    protected $table = 'web_location';
    //主鍵
    protected $primaryKey = 'id';
    
    /*public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }*/

    public function getWebLocationAttribute($store_name)
    {
        if (is_string($store_name)) {
            return json_decode($store_name, true);
        }

        return $store_name;
    }

    public function setWebLocationAttribute($store_name)
    {
        if (is_array($store_name)) {
            $this->attributes['store_name'] = json_encode($store_name);
        }
    }

}
