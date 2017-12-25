<?php

namespace App\Admin\Extensions;

use Encore\Admin\Grid\Exporters\AbstractExporter;
use Illuminate\Support\Arr;
use Maatwebsite\Excel\Facades\Excel;

class ExcelExpoter extends AbstractExporter
{
    protected $title = [
        'en' => [],
        'zh' => [],
    ]; 
    public function export()
    {
        $filename = trans('admin::lang.'.$this->getTable());
    
        Excel::create($filename, function($excel) {
        
            
            $excel->sheet((date("Y")-1911).date("md"), function($sheet){

                
                $sheet->row(1,$titlename);
                $rows = collect($this->getData())->map(function ($item) {
                    // return array_except($item, ['showfront', 'shownew', 'showsales', 'update_user','created_at','updated_at','deleted_at','stock','p_category','p_series','p_images','p_pic','p_description','stock']);
                    return array_only($item, ['s_stock', 'p_name', 'p_number', 'p_stock']);
                });
                $sheet->rows($rows);
            });
            $excel->setCreator('大創娃娃屋')->setTitle('')->setDescription('');
        })->export('xlsx');
    }


    /**
     * Remove indexed array.
     *
     * @param array $row
     *
     * @return array
     */
    protected function sanitize(array $row)
    {
        return collect($row)->reject(function ($val) {
            return is_array($val) && !Arr::isAssoc($val);
        })->toArray();
    }

    /**
     * @param $row
     * @param string $fd
     * @param string $quot
     *
     * @return string
     */
    protected static function putcsv($row, $fd = ',', $quot = '"')
    {
        $str = '';
        foreach ($row as $cell) {
            $cell = str_replace([$quot, "\n"], [$quot.$quot, ''], $cell);
            if (strstr($cell, $fd) !== false || strstr($cell, $quot) !== false) {
                $str .= $quot.$cell.$quot.$fd;
            } else {
                $str .= $cell.$fd;
            }
        }

        return substr($str, 0, -1)."\n";
    }
}