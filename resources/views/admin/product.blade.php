<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">{{$description}}</h3>
    </div>

    <!-- /.box-header -->
    <div class="box-body">

        <form method="post" action="{{--$action--}}" id="scaffold" pjax-container>

             {{--<div class="box-body">

                <div class="form-horizontal">

                <div class="form-group">

                    <label for="inputAssignDate" class="col-sm-2 control-label">日期</label>

                    <div class="col-sm-4">
                       <input type="date" name="assign_date" class="form-control" id="inputAssignDate" placeholder="assign date" value="{{old('assign_date')}}">
                    </div>

                    <span class="help-block hide" id="table-name-help">
                        <i class="fa fa-info"></i>&nbsp; Table name can't be empty!
                    </span>

                </div>
                <div class="form-group">
                    <label for="inputModelName" class="col-sm-2 control-label">單號</label>

                    <div class="col-sm-4">
                        <input type="text" name="assign_id" class="form-control" id="inputAssignId" placeholder="model" value="{{ old('assign_id', "App\\Models\\") }}">
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputAssignNotes" class="col-sm-2 control-label">備註</label>
                    <div class="col-sm-4">
                        <textarea class="form-control input-oneLineHigh ng-pristine ng-valid ng-touched" formcontrolname="Memo" id="Memo" maxlength="255" placeholder="" style="margin-top: 0px; margin-bottom: 0px; height: 30px;"></textarea>
                    </div>
                </div> --}}

                {{-- <h4>商品清單</h4> --}}
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
                        <tbody>
                        @if(old('fields'))
                            @foreach(old('fields') as $index => $field)
                                <tr>
                                    <td>
                                        <div>{{$index}}</div>
                                    </td>
                                    <td>
                                        <input type="text" name="fields[{{$index}}][name]" class="form-control" placeholder="field name" value="{{$field['name']}}" />
                                    </td>
                                    <td>
                                        <select style="width: 200px" name="fields[{{$index}}][type]">
                                        </select>
                                    </td>
                                    <td><input type="checkbox" name="fields[{{$index}}][nullable]" {{ array_get($field, 'nullable') == 'on' ? 'checked': '' }}/></td>
                                    <td>
                                        <select style="width: 150px" name="fields[{{$index}}][key]">
                                            {{--<option value="primary">Primary</option>--}}
                                            <option value="" {{$field['key'] == '' ? 'selected' : '' }}>NULL</option>
                                            <option value="unique" {{$field['key'] == 'unique' ? 'selected' : '' }}>Unique</option>
                                            <option value="index" {{$field['key'] == 'index' ? 'selected' : '' }}>Index</option>
                                        </select>
                                    </td>
                                    <td><input type="text" class="form-control" placeholder="default value" name="fields[{{$index}}][default]" value="{{$field['default']}}"/></td>
                                    <td><input type="text" class="form-control" placeholder="assign_notes" name="fields[{{$index}}][assign_notes]" value="{{$field['assign_notes']}}" /></td>
                                    <td><a class="btn btn-sm btn-danger table-field-remove"><i class="fa fa-trash"></i> 移除</a></td>
                                </tr>
                            @endforeach
                        @else
                        <tr>
                            <td>
                                <div>{{$count=1}}</div>
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
                        @endif
                        </tbody>
                    </table>
                </div>
                <hr style="margin-top: 0;"/>

                <div class='form-inline margin' style="width: 100%">
                    <div class='form-group'>
                        <button type="button" class="btn btn-sm btn-warning" id="add-table-field"><i class="fa fa-plus"></i>&nbsp;&nbsp;新增</button>
                    </div>
                </div>

            </div>

            <!-- /.box-body -->
            <div class="box-footer">
                <center>
                    <button type="submit" class="btn btn-success" data-loading-text="<i class='fa fa-spinner fa-spin '></i> 儲存">儲存 <i class="fa fa-check"></i></button>
                    <button type="button"  class="btn btn-danger">取消 <i class="fa fa fa-times"> </i> </button>
                </center>
            </div>
            {{ csrf_field() }}

            <!-- /.box-footer -->
        </form>

    </div>

</div>

<template id="table-field-tpl">
    <tr>
        <td>
            <div>{{$count}}</div>
        </td>
        <td>
            <input type="text" name="fields[__index__][name]" class="form-control" placeholder="field name" />
        </td>
        <td>
            <select style="width: 200px" name="fields[__index__][type]">
                {{-- @foreach($dbTypes as $type)
                    <option value="{{ $type }}">{{$type}}</option>
                @endforeach --}}
            </select>
        </td>
        <td><input type="checkbox" name="fields[__index__][nullable]" /></td>
        <td>
            <select style="width: 150px" name="fields[__index__][key]">
                <option value="" selected>NULL</option>
                <option value="unique">Unique</option>
                <option value="index">Index</option>
            </select>
        </td>
        <td><input type="text" class="form-control" placeholder="default value" name="fields[__index__][default]"></td>
        <td><input type="text" class="form-control" placeholder="comment" name="fields[__index__][comment]"></td>
        <td><a class="btn btn-sm btn-danger table-field-remove"><i class="fa fa-trash"></i> 移除</a></td>
    </tr>
</template>

<template id="model-relation-tpl">
    <tr>
        <td><input type="text" class="form-control" placeholder="relation name" value=""></td>
        <td>
            <select style="width: 150px">
                <option value="HasOne" selected>HasOne</option>
                <option value="BelongsTo">BelongsTo</option>
                <option value="HasMany">HasMany</option>
                <option value="BelongsToMany">BelongsToMany</option>
            </select>
        </td>
        <td><input type="text" class="form-control" placeholder="related model"></td>
        <td><input type="text" class="form-control" placeholder="default value"></td>
        <td><input type="text" class="form-control" placeholder="default value"></td>
        <td><input type="checkbox" /></td>
        <td><a class="btn btn-sm btn-danger model-relation-remove"><i class="fa fa-trash"></i> 移除</a></td>
    </tr>
</template>

{{-- 新增 --}}
<script>

$(function () {

    $('input[type=checkbox]').iCheck({checkboxClass:'icheckbox_minimal-blue'});
    $('select').select2();

    $('#add-table-field').click(function (event) {
        $('#table-fields tbody').append($('#table-field-tpl').html().replace(/__index__/g, $('#table-fields tr').length - 1));
        $('select').select2();
        $('input[type=checkbox]').iCheck({checkboxClass:'icheckbox_minimal-blue'});
    });

    $('#table-fields').on('click', '.table-field-remove', function(event) {
        $(event.target).closest('tr').remove();
    });

    $('#add-model-relation').click(function (event) {
        $('#model-relations tbody').append($('#model-relation-tpl').html().replace(/__index__/g, $('#model-relations tr').length - 1));
        $('select').select2();
        $('input[type=checkbox]').iCheck({checkboxClass:'icheckbox_minimal-blue'});

        relation_count++;
    });

    $('#model-relations').on('click', '.model-relation-remove', function(event) {
        $(event.target).closest('tr').remove();
    });

    $('#scaffold').on('submit', function (event) {

        //event.preventDefault();

        if ($('#inputTableName').val() == '') {
            $('#inputTableName').closest('.form-group').addClass('has-error');
            $('#table-name-help').removeClass('hide');

            return false;
        }

        return true;
    });
});

</script>

