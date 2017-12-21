<?php

namespace App;

use Encore\Admin\Traits\AdminBuilder;
use Encore\Admin\Traits\ModelTree;
use Illuminate\Database\Eloquent\Model;

class WebArea extends Model
{
	use ModelTree, AdminBuilder;
    //地區資料表
    protected $table = 'web_area';
    //主鍵
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setParentColumn('parent_id');
        $this->setOrderColumn('area_sort');
        $this->setTitleColumn('area_name');
    }

    // public function scopeProvince()
    // {
    //     return $this->where('type', 1);
    // }

    public function scopeCity()
    {
        return $this->where('type', 1);
    }

    public function scopeDistrict()
    {
        return $this->where('type', 2);
	}

    public function parent()
    {
        return $this->belongsTo(WebArea::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(WebArea::class, 'parent_id');
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
