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
            <button class="btn btn-sm btn-primary" type="submit" onclick="$('.loading-fullpage').show();"><i class="fa fa-upload"></i>&nbsp;&nbsp;{$import}</button>
        </div>
    </form>
</div>
<div class="loading-fullpage" style="display: none;">
<div class="loading-circle">
    <div class="loading-circle1 loading-child"></div>
    <div class="loading-circle2 loading-child"></div>
    <div class="loading-circle3 loading-child"></div>
    <div class="loading-circle4 loading-child"></div>
    <div class="loading-circle5 loading-child"></div>
    <div class="loading-circle6 loading-child"></div>
    <div class="loading-circle7 loading-child"></div>
    <div class="loading-circle8 loading-child"></div>
    <div class="loading-circle9 loading-child"></div>
    <div class="loading-circle10 loading-child"></div>
    <div class="loading-circle11 loading-child"></div>
    <div class="loading-circle12 loading-child"></div>
</div>
</div>

EOT;
    }
}
