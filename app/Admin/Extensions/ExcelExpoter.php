<?php

namespace App\Admin\Extensions;

use Encore\Admin\Grid\Exporters\AbstractExporter;
use Maatwebsite\Excel\Facades\Excel;

class ExcelExpoter extends AbstractExporter
{
    public function export()
    {
        Excel::create('Filename', function($excel) {
            $excel->setCreator('大創娃娃屋')->setTitle('')->setDescription('');

            $excel->sheet(date("Y-m-d"), function($sheet) {
                // 这段逻辑是从表格数据中取出需要导出的字段
                $rows = collect($this->getData())->map(function ($item) {
                    return array_only($item, ['pid', 'p_name', 'p_number', 'p_stock']);
                });

                $sheet->rows($rows);

            });
        })->export('xls');
    }
}