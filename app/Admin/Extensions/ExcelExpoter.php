<?php

namespace App\Admin\Extensions;

use Encore\Admin\Grid\Exporters\AbstractExporter;
use Illuminate\Support\Arr;
use Maatwebsite\Excel\Facades\Excel;

class ExcelExpoter extends AbstractExporter
{
    protected $filename;
    protected $titles = [];

    public function setDetails($titles, $filename)
    {
        $this->titles = $titles;
        $this->filename = $filename;
        return $this;
    }

    public function export()
    {
        $titlename = [];

        foreach($this->titles as $key => $title){
            $titlename[$key] = trans('admin::lang.'.$title);
        }

        $filename = $this->filename ?: trans('admin::lang.'.$this->getTable());
    
        Excel::create($filename, function($excel) use ($titlename){        
            
            $excel->sheet((date("Y")-1911).date("md"), function($sheet) use ($titlename){

                //第一列 標題
                $sheet->row(1,$titlename);

                $rows = collect($this->getData())->map(function ($item) {

                    /**
                     * array_only($item, [])第二個參數可以是陣列，但是順序不按照第二個陣列參數，而是依照參數$item做排序
                     * 為了內容與標題一致，所以改用foreach去一行一行取資料...
                    */
                    $output = [];
                    foreach($this->titles as $key => $title){
                        $output = array_merge($output,array_only($item, $title));
                    }                    
                    return $output;
                });
                $sheet->rows($rows);
            });
            $excel->setCreator('大創娃娃屋')->setTitle('123')->setDescription('456');
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

}