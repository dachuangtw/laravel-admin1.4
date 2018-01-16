<?php

namespace App;

use Encore\Admin\Traits\AdminBuilder;
use Encore\Admin\Traits\ModelTree;
use Illuminate\Database\Eloquent\Model;

class WebArea extends Model
{
    use ModelTree, AdminBuilder;
    //DB2
    protected $connection = 'mysql2';
    //地區資料表
    protected $table = 'tw_area';
    //主鍵
    protected $primaryKey = 'id';
    public $timestamps = false;

    //tree
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setParentColumn('parent_id');
        $this->setOrderColumn('area_sort');
        $this->setTitleColumn('area_name');
    }
 
    public function scopeCity()
    {
        return $this->where('type','1');
    }
    
    public function scopeDistrict()
    {
        return $this->where('type','2');
    }

    public function children()
    {
        return $this->hasMany(WebArea::class, 'id');
    }

    public function parent()
    {
        return $this->belongsTo(WebArea::class, 'id');
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
        return $self->brothers()->pluck('area_name', 'id');
    }
}
