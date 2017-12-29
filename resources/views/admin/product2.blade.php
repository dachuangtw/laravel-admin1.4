<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">{{ $form->title() }}</h3>

        <div class="box-tools">
            {!! $form->renderHeaderTools() !!}
        </div>
    </div>
    <!-- /.box-header -->
    <!-- form start -->
    {!! $form->open(['class' => "form-horizontal"]) !!}
        <div class="box-body">

            @if(!$tabObj->isEmpty())
                @include('admin::form.tab', compact('tabObj'))
            @else
                <div class="fields-group">
                    @foreach($form->fields() as $field)
                        {!! $field->render() !!}
                    @endforeach
                </div>
            @endif

            <!-- form table start -->
            <h4>商品清單</h4>
            <div class="table-responsive">
                <table class="table table-hover" id="table-fields" >
                    <tr>
                        <th>序</th>
                        <th>品號</th>
                        <th style="width: 200px">品名</th>
                        <th>單位</th>
                        <th>數量</th>
                        <th>單價</th>
                        <th>金額</th>
                        <th>操作</th>
                    </tr>
                    <tr>
                        <td>
                            <div>1</div>
                        </td>
                        <td>
                            <input type="text" name="fields[0][name]" class="form-control" placeholder="field name" />
                        </td>
                        <td>
                            <select style="width: 200px" name="fields[0][type]">
                            </select>
                        </td>
                        <td><input type="checkbox" name="fields[0][nullable]" /></td>
                        <td>
                            <select style="width: 150px" name="fields[0][key]">
                            </select>
                        </td>
                        {{-- <td><input type="text" class="form-control" placeholder="default value" name="fields[0][default]"></td>--}}
                        <td>
                            <div class="input-group">
                              <div class="input-group-addon">$</div>
                              <input type="text" class="form-control" id="exampleInputAmount" placeholder="單價" style="width: 100px">
                            </div>
                        </td>
                        {{-- <td><input type="text" class="form-control" placeholder="comment" name="fields[0][comment]"></td> --}}
                        <td>
                            <div class="input-group">
                              <div class="input-group-addon">$</div>
                              <input type="text" class="form-control" id="exampleInputAmount" placeholder="金額" style="width: 100px">
                            </div>
                        </td>
                        <td><a class="btn btn-sm btn-danger table-field-remove"><i class="fa fa-trash"></i> 移除</a></td>
                    </tr>
                </table>

            </div>
            <div class='form-inline margin' style="width: 100%">
                <div class='form-group'>
                    <button type="button" class="btn btn-sm btn-warning" id="add-table-field"><i class="fa fa-plus"></i>&nbsp;&nbsp;新增</button>
                </div>
            </div>
        </div>

        <!-- /.box-body -->
        <div class="box-footer">

            @if( ! $form->isMode(\Encore\Admin\Form\Builder::MODE_VIEW)  || ! $form->option('enableSubmit'))
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
            @endif
            <div class="col-sm-{{$width['label']}}">

            </div>
            <div class="col-sm-{{$width['field']}}" style="text-align: center;">

                {!! $form->submitButton() !!}

                @if(! $form->option('enableSubmit'))
                {!! $form->resetButton() !!}
                {!! $form->searchButton() !!}
                @endif
            </div>

        </div>

        @foreach($form->getHiddenFields() as $hiddenField)
            {!! $hiddenField->render() !!}
        @endforeach

        <!-- /.box-footer -->
    {!! $form->close() !!}
</div>

