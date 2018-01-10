<?php

namespace App;

use Encore\Admin\Traits\AdminBuilder;
use Encore\Admin\Traits\ModelTree;
use Illuminate\Database\Eloquent\Model;

class WebArea extends Model
{
    //DB2
    protected $connection = 'mysql2';
    //地區資料表
    protected $table = 'district';
    //主鍵
    protected $primaryKey = 'district_id';
    public $timestamps = false;
    
    public function children()
    {
        return $this->hasMany(WebArea::class, 'city_id');
    }

    public function parent()
    {
        return $this->belongsTo(Location::class, 'city_id');
    }

    public function brothers()
    {
        return $this->parent->children();
    }
    
    public static function options($id)
    {
        if (! $self = static::find($id)) {
            return [];
        }
        return $self->brothers()->pluck('district_name', 'district_id');
    }
}
