<?php

namespace Encore\Admin\Grid\Exporters;

use Illuminate\Support\Arr;

class CsvExporter extends AbstractExporter
{
    /**
     * {@inheritdoc}
     */
    public function export()
    {
        $titles = [];

        $filename = trans('admin::lang.'.$this->getTable()).'.csv';

        $data = $this->getData();

        if (!empty($data)) {
            $columns = array_dot($this->sanitize($data[0]));

            $titles = array_keys($columns);
        }

        $output = self::putcsv($titles);

        foreach ($data as $row) {
            $row = array_only($row, $titles);
            $output .= self::putcsv(array_dot($row));
        }

        $headers = [
            'Content-Encoding'    => 'UTF-8',
            'Content-Type'        => 'text/csv;charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        /*中文亂碼問題
          依照 http://chiahan.logdown.com/posts/280868/web-php-export-csv-chinese-encoding-problem
          加"\xEF\xBB\xBF"在output之前*/
        response(rtrim("\xEF\xBB\xBF".$output, "\n"), 200, $headers)->send();

        exit;
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
