<?php

namespace Encore\Admin\Grid\Tools;

use Encore\Admin\Grid;

class ImportButton extends AbstractTool
{
    /**
     * Create a new Export button instance.
     *
     * @param Grid $grid
     */
    public function __construct(Grid $grid)
    {
        $this->grid = $grid;
    }

    /**
     * Render Export button.
     *
     * @return string
     */
    public function render()
    {
        if (!$this->grid->allowImport()) {
            return '';
        }

        $import = trans('admin::lang.import');
        $csrf_field = csrf_field();
        

        return <<<EOT

<div class="btn-group pull-right" style="margin-right: 10px">
    <form action="./product/import" method="post" enctype="multipart/form-data">
        <div class="col-md-6">
            {$csrf_field}
            <input type="file" name="imported-file"/>
        </div>
        <div class="col-md-6">
            <button class="btn btn-primary" type="submit"><i class="fa fa-upload"></i>&nbsp;&nbsp;{$import}</button>
        </div>
    </form>
</div>

EOT;
    }
}
