<form {!! $attributes !!}>
    <div class="box-body fields-group">

        @foreach($fields as $field)
            {!! $field->render() !!}
        @endforeach

    </div>

    <!-- /.box-body -->
    <div class="box-footer">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="col-sm-2">
        </div>
        @if($buttons['enableSubmit'])
            <div class="col-sm-4">
                <div class="btn-group pull-right">
                    <button type="submit" class="btn btn-success pull-right">{{ trans('admin::lang.submit') }} <i class="fa fa-check"></i></button>
                </div>
            </div>
        @endif

        @if($buttons['enableSearch'])
            <div class="col-sm-4">
                <div class="btn-group pull-right">
                    <button type="submit" class="btn btn-success pull-right">{{ trans('admin::lang.search') }} <i class="fa fa-search"></i></button>
                </div>
            </div>
        @endif
        @if($buttons['enableReset'])
            <div class="col-sm-2">
                <div class="btn-group pull-left">
                    <button type="reset" class="btn btn-warning pull-right">{{ trans('admin::lang.reset') }}</button>
                </div>
            </div>
        @endif

    </div>
</form>