<?php

/**
 * Laravel-admin - admin builder based on Laravel.
 * @author z-song <https://github.com/z-song>
 *
 * Bootstraper for Admin.
 *
 * Here you can remove builtin form field:
 * Encore\Admin\Form::forget(['map', 'editor']);
 *
 * Or extend custom form field:
 * Encore\Admin\Form::extend('php', PHPEditor::class);
 *
 * Or require js and css assets:
 * Admin::css('/packages/prettydocs/css/styles.css');
 * Admin::js('/packages/prettydocs/js/main.js');
 *
 */
use App\Admin\Extensions\Column\ExpandRow;
use Encore\Admin\Grid\Column;

//Encore\Admin\Form::forget(['map', 'editor']);
app('view')->prependNamespace('admin', resource_path('views/admin'));
Admin::css('css/customize.css');
Admin::css('css/ajaxlivesearch.css');

Admin::js('js/customize.js');
Admin::js('js/popper.min.js');
Admin::js('js/ajaxlivesearch.js');

//擴展
Column::extend('expand', ExpandRow::class);