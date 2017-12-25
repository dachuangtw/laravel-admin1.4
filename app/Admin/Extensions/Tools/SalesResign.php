<?php

namespace App\Admin\Extensions\Tools;

use Encore\Admin\Admin;
use Encore\Admin\Grid\Tools\AbstractTool;
use Illuminate\Support\Facades\Request;

class SalesResign extends AbstractTool
{
    public function script()
    {
        $url = Request::fullUrlWithQuery(['resign' => '_resign_']);

        return <<<EOT

$('input:radio.sales-resign').change(function () {

    var url = "$url".replace('_resign_', $(this).val());

    $.pjax({container:'#pjax-container', url: url });

});

EOT;
    }

    public function render()
    {
        Admin::script($this->script());

        $options = [
            'all'   => '全部',
            '0'     => '在職',
            '1'     => '離職',
        ];

        return view('admin.tools.resign', compact('options'));
    }
}
