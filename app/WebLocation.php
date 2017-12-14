<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Encore\Admin\Traits\ModelTree;
use Encore\Admin\Traits\AdminBuilder;

class WebLocation extends Model
{
	use ModelTree, AdminBuilder;

    //店鋪據點資料表
    protected $table = 'web_location';
    //主鍵
    protected $primaryKey = 'id';

	// tree srot
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setParentColumn('store_area'); //parent_id
        $this->setOrderColumn('store_sort'); //order
        $this->setTitleColumn('store_name'); //title
    }

    public function web_area()
    {
        return $this->belongsTo(WebArea::class);
    }
}
