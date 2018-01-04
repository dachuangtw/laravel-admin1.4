<?php

namespace App\Admin\Extensions\Tools;

use Encore\Admin\Admin;
use Encore\Admin\Grid\Tools\AbstractTool;
use Illuminate\Support\Facades\Request;

class DateChooser extends AbstractTool
{
    protected function script()
    {
        $url = Request::fullUrlWithQuery(['assign_date' => '_assign_date_']);

        return <<<EOT

$('input:radio.date-chooser').change(function () {

    var url = "$url".replace('_assign_date_', $(this).val());

    $.pjax({container:'#pjax-container', url: url });

});

EOT;
    }

    public function render()
    {
        Admin::script($this->script());
        $today = date('Ymd');
        $yesterday = date('Ymd')-1;
        // $thismonth_start=mktime(date('m'),1,date('Y'));
        // $thismonth_end=mktime(date('m'),date('t'),date('Y'));    
        $options = [
            // 'thismonth'   => '本月',
            // 'lastmonth'   => '上月',
            // 'lastweek'    => '上周',
            // 'thisweek'    => '本周',
            'all'        => '全部',
            $yesterday   => '昨日',
            $today       => '今日',
        ];

        return view('admin.tools.datechooser', compact('options'));
    }
}