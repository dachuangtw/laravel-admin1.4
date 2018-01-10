<?php

namespace App\Admin\Extensions;

use Encore\Admin\Auth\Database\Administrator;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Grid\Exporters\AbstractExporter;
use Illuminate\Support\Arr;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;

class ExcelExpoter extends AbstractExporter
{
    protected $creator;
    protected $filename;
    protected $titles = [];
    protected $maxwidth;
    protected $foreignKeys = [];

    public function setDetails($titles, $filename, $creator)
    {
        $this->creator = $creator;
        $this->filename = $filename;
        $this->titles = $titles;
    }

    public function setForeignKeys($foreignKeys)
    {
        /**
         * 外部鍵陣列範例：
         * $foreignKeys => [
         *      [supid] => [
         *          [dbname] => 'product_supplier',
         *          [id] => 'supid',
         *          [target] => 'sup_name',
         *      ],
         *      [wid] => [
         *          [dbname] => 'warehouse',
         *          [id] => 'wid',
         *          [target] => 'w_name',
         *      ],
         * ]
         * 
         * Notice：'dbname' =>  'Admin' 另外設定user->{{$foreignKeys['target']}}
         * (['target'] 可以是name、wid、id...etc)
         */
        $this->foreignKeys = $foreignKeys;
    }

    public function export()
    {
        $this->maxwidth = $this->num2alpha(count($this->titles)-1);
        $titlename = [];

        foreach($this->titles as $key => $title){
            $titlename[$key] = trans('admin::lang.'.$title);
        }        

        $filename = $this->filename ?: trans('admin::lang.'.$this->getTable());
    
        Excel::create($filename, function($excel) use ($titlename){        
            
            
            $excel->sheet((date("Y")-1911).date("md"), function($sheet) use ($titlename){
                //第一列 標題
                $sheet->row(1,$titlename);
                $sheet->row(1,function($row){
                    $row->setFontWeight('bold');
                });

                $rows = collect($this->getData())->map(function ($item) {

                    /**
                     * array_only($item, [])第二個參數可以是陣列，但是順序不按照第二個陣列參數，而是依照參數$item做排序
                     * 為了內容與標題一致，所以改用foreach去一行一行取資料...
                    */
                    $output = [];
                    foreach($this->titles as $key => $title){
                        $tempoutput = array_only($item, $title);

                        //如果是外部鍵從id轉換成name
                        if(isset($this->foreignKeys[$title])){
                            if($this->foreignKeys[$title]['dbname'] != 'Admin'){
                                $foreignNames = DB::table($this->foreignKeys[$title]['dbname'])->pluck($this->foreignKeys[$title]['target'], $this->foreignKeys[$title]['id']);
                            }
                            foreach($tempoutput as $key2 => $value2){
                                if($this->foreignKeys[$title]['dbname'] != 'Admin'){
                                    $tempoutput[$key2] = $foreignNames[$value2];
                                }else{
                                    $tempoutput[$key2] = Administrator::find($value2)->{$this->foreignKeys[$title]['target']}; 
                                }
                            }
                        }


                        $output = array_merge($output,$tempoutput);
                    }

                    foreach($output as $key => $content){

                        //前四字為show開頭的欄位，判斷1=顯示，0=隱藏
                        if(mb_substr($key,0,4,"utf-8") === 'show'){
                            $output[$key] = $content ? '顯示' : '隱藏' ;
                        }
                    }
                    return $output;
                });

                //第二列開始 內容
                $rowName = 2;
                foreach($rows as $row){
                    $columName = 0;//A
                    foreach($row as $content){

                        $sheet->cell($this->num2alpha($columName).$rowName, function($cell) use ($content){
                            
                            $cell->setValue($content);
                            if($content === '隱藏'){
                                $cell->setFontColor('#ff0000');
                            }
                            
                            $cell->setValignment('middle');
                        });
                        $columName++;
                    }

                    $rowName++;
                }                
                // $sheet->setAutoFilter();
                $sheet->setAutoSize(false);
                // $sheet->rows($rows);
            });
            $excel->setCreator($this->creator)->setTitle($this->filename)->setSubject($this->filename);
        })->export('xlsx'); 
    }

    public function num2alpha($n)  //數字轉英文(0=>A、1=>B、26=>AA...以此類推)
    {
        for($r = ""; $n >= 0; $n = intval($n / 26) - 1)
            $r = chr($n%26 + 0x41) . $r; 
        return $r; 
    }   
}