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

        $options = [
          
            'all'         => '全部',
            'thismonth'   => '本月',
            'lastmonth'   => '上月',
        ];

        return view('admin.tools.datechooser', compact('options'));
    }
    
}