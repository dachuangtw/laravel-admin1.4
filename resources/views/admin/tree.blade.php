<div class="box">

    <div class="box-header">

        <div class="btn-group">
            <a class="btn btn-primary btn-sm {{ $id }}-tree-tools" data-action="expand">
                <i class="fa fa-plus-square-o"></i>&nbsp;{{ trans('admin::lang.expand') }}
            </a>
            <a class="btn btn-primary btn-sm {{ $id }}-tree-tools" data-action="collapse">
                <i class="fa fa-minus-square-o"></i>&nbsp;{{ trans('admin::lang.collapse') }}
            </a>
        </div>

        <div class="btn-group">
            <a class="btn btn-info btn-sm  {{ $id }}-save"><i class="fa fa-save"></i>&nbsp;{{ trans('admin::lang.save') }}</a>
        </div>

        <div class="btn-group">
            <a class="btn btn-warning btn-sm {{ $id }}-refresh"><i class="fa fa-refresh"></i>&nbsp;{{ trans('admin::lang.refresh') }}</a>
        </div>

        <div class="btn-group">
            {!! $tools !!}
        </div>

        @if($useCreate)
        <div class="btn-group pull-right">
            <a class="btn btn-success btn-sm" href="{{ $path }}/create"><i class="fa fa-save"></i>&nbsp;{{ trans('admin::lang.new') }}</a>
        </div>
        @endif

    </div>
    <!-- /.box-header -->
    <div class='modal fade' id="viewmodal">
    <div class='modal-dialog modal-lg'>
        <div class='modal-content'>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"> &times; </button>
                <h3>{{ trans('admin::lang.view') }}</h3>
            </div>
            <div class='modal-body'>彈出式視窗(網頁載入)</div>
            <div class='modal-footer'>
                <button class='btn btn-default' data-dismiss="modal" aria-hidden="true">{{ trans('admin::lang.close') }}</button>
            </div>
        </div>
    </div>
    </div>
    <!-- /.modal -->
    <div class="box-body table-responsive no-padding">
        <div class="dd" id="{{ $id }}">
            <ol class="dd-list">
                @each($branchView, $items, 'branch')
            </ol>
        </div>
    </div>
    <!-- /.box-body -->
</div>