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

        @if(!$tabObj->isEmpty()) @include('admin::form.tab', compact('tabObj')) @else
        <div class="fields-group">
            @foreach($form->fields() as $field) {!! $field->render() !!} @endforeach
        </div>
        @endif
        {{--  <div class="card">  --}}
        <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#addManyRowModal">批量  <i class="fa fa-plus-square-o"></i></button>                            
        <button type="button" class="btn btn-primary" id="add-more" onClick="AddRow();">新增 <i class="fa fa-plus"></i></button>
        <button type="button" class="btn btn-danger" id="clear-all-raw" onClick="ClearAllRow();">清空 <i class="fa fa-trash-o"></i></button>    
        <div class="card-header text-center">
            <font size="5">
                <b>進貨單明細</b>
            </font>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <div class="col-md-12 col-sm-6">
                    <table class="rwd-table table table-hover" id="product-table">
                        <tr>
                            <th style="width:30px;">操作</th>
                            <th style="width:30px;">序</th>
                            <th>產品編號</th>
                            <th style="width: 200px;">品項名稱</th>
                            <th>分類</th>
                            <th style="width:100px;">進貨數量</th>
                            <th style="width:100px;">成本價</th>
                            <th style="width:100px;">業務價</th>
                            <th style="width:100px; display:none;">南台價</th>
                            <th style="width:150px; display:none;">售價</th>
                            <th style="width:130px;">成本價金額</th>
                            <th style="width:130px;">業務價金額</th>
                            <th style="width:100px;">備註</th>
                        </tr>                        
                        <tbody id="table-body">
                            @foreach ( $extradata['savedDetails'] as $k => $value)
                            <tr class="table-row" id="table-row-{{$k+1}}">
                                <td data-th="操作">
                                    <a class="btn btn-xs btn-success" id="add-more" onClick="AddRow();" title="新增">
                                        <i class="fa fa-arrow-right"></i>
                                    </a>
                                    <a class="btn btn-xs btn-danger removerow" onclick="deleteRecord({{$k+1}});" title="刪除">
                                        <i class="fa fa-times"></i>
                                    </a>
                                </td>
                                <td data-th="序">{{$k+1}}</td>
                                <td data-th="產品編號" id="p-number-{{$k+1}}">
                                     {{$extradata['products'][$k]->p_number}}
                                </td>
                                <td data-th="品項名稱">
                                    <input type="hidden" name="pid[]" value="{{$value->pid}}">
                                    <input type="hidden" name="redid[]" value="{{$value->redid}}">
                                    <input type="text" class="form-control" name="p_name[]" placeholder="輸入品名" required="required" style="width: 200px;" value="{{$extradata['products'][$k]->p_name}}">
                                </td>
                                <td data-th="分類">
                                    <select class="form-control" name="category[]">
                                    @foreach($extradata['StockCategory'] as $sc_number => $sc_name)
                                        @if($sc_number == $extradata['products'][$k]->category)
                                            <option value="{{$sc_number}}" selected>{{$sc_name}}</option>
                                        @else
                                            <option value="{{$sc_number}}">{{$sc_name}}</option>
                                        @endif                                        
                                    @endforeach
                                    </select>
                                </td>
                                <td data-th="數量">
                                    <input type="number" class="form-control" name="quantity[]" id="quantity-{{$k+1}}" placeholder="數量" required="required" onChange='sumPrice({{$k+1}})' min="0" value="{{$value->red_quantity}}" style="width:100px;"> </td>
                                </td>
                                <td data-th="成本價">
                                    <input type="number" class="form-control" name="costprice[]" id="cost-price-{{$k+1}}" placeholder="成本價" required="required" onChange='sumPrice({{$k+1}})' min="0" value="{{$value->red_price}}" style="width:100px;">
                                </td>
                                <td data-th="業務價">
                                    <input type="number" class="form-control" name="salesprice[]" id="sales-price-{{$k+1}}" placeholder="業務價" required="required" onChange='sumPrice({{$k+1}})' min="0" value="{{$extradata['products'][$k]->p_salesprice}}" style="width:100px;">
                                </td>
                                <td data-th="南台價" style="display:none">
                                    {{-- <input type="number" class="form-control" name="southprice[]" placeholder="南台價" required="required" min="0" value="{{$extradata['products'][$k]->p_southprice}}" style="width:100px;"> --}}
                                </td>
                                <td data-th="售價" style="display:none">
                                    <input type="number" class="form-control" name="retailprice[]" placeholder="售價" required="required" min="0" value="{{$extradata['products'][$k]->p_retailprice}}" style="width:100px;">
                                </td>
                                <td data-th="成本價金額"><input type="text" class="form-control" name="sumcostprice[]" id="sumcostprice{{$k+1}}" onChange='sumPrice({{$k+1}})' value="{{$value->sumcostprice}}" style="width:130px;"></td>
                                <td data-th="業務價金額"><input type="text" class="form-control" name="sumsalesprice[]" id="sumsalesprice{{$k+1}}" onChange='sumPrice({{$k+1}})' value="{{$value->sumsalesprice}}" style="width:130px;"></td>
                                <td data-th="備註">
                                    <textarea class="form-control" name="notes[]" rows="1" placeholder="備註" style="width:100px;">{{$value->red_notes}}</textarea>
                                </td>
                            </tr>
                            @endforeach
                            </tbody>
                    </table>
                    {{--  暫存計數rowid 判斷資料表是否有資料 初始值為count($extradata['savedDetails']) || 初始值為1  --}}
                    <input name='txtTRLastIndex' type='hidden' id='txtTRLastIndex' value="{{count($extradata['savedDetails'])+1}}"> 
                </div>
            </div>
        </div>
    </div>
    
    <div class="row text-center" id="total"  style="font-weight: bold;">
        <div class="col-md-3"></div>
        <div class="col-md-3">
            <div class="col-md-12">總數量：<span>0</span></div>
        </div>
        <div class="col-md-3">
            <div class="col-md-12">成本價合計金額：<span>0</span> 元</div>
        </div>
        <div class="col-md-3">
            <div class="col-md-12">業務價合計金額：<span>0</span> 元</div>
        </div>
    </div>

    <!-- /.box-body -->
    <div class="box-footer">

        @if( ! $form->isMode(\Encore\Admin\Form\Builder::MODE_VIEW) || ! $form->option('enableSubmit'))
        <input type="hidden" name="_token" value="{{ csrf_token() }}"> @endif
        <div class="col-sm-{{$width['label']}}">

        </div>

        <div class="col-sm-{{$width['field']}}" style="text-align: center;">

            {!! $form->submitButton() !!} @if(! $form->option('enableSubmit')) {!! $form->resetButton() !!} {!! $form->searchButton()
            !!} @endif



        </div>

    </div>

    @foreach($form->getHiddenFields() as $hiddenField) {!! $hiddenField->render() !!} @endforeach

    <!-- /.box-footer -->
    {!! $form->close() !!}
</div>

{{-- 新增多列 modal --}}
<div class="modal fade" id="addManyRowModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
        <h4 class="modal-title">批量新增</h4>
      </div>
      <div class="modal-body">
        <form>
          <div class="form-group">
            <label class="col-form-label">新增商品項目數量:</label>
            <input type="number" class="form-control" id="add-quantity" min="1" value="1">
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">關閉</button>
        <button type="button" class="btn btn-primary" data-dismiss="modal" onClick="AddManyRow();">新增</button>
      </div>
    </div>
  </div>
</div>


<style>
    .rwd-table {
        background: #fff;
        overflow: hidden;
    }

    .rwd-table tr:nth-of-type(2n) {
        background: #eee;
    }

    .rwd-table th,
    .rwd-table td {
        margin: 0.5em 1em;
    }

    .rwd-table {
        min-width: 100%;
    }

    .rwd-table th {
        display: none;
    }

    .rwd-table td {
        display: block;
    }

    .rwd-table td:before {
        content: attr(data-th) " : ";
        font-weight: bold;
        width: 6.5em;
        display: inline-block;
    }

    .rwd-table th,
    .rwd-table td {
        /* text-align: center; */
        text-align: left;
        white-space: nowrap;
        /* font-weight: bold; */
    }

    .rwd-table th,
    .rwd-table td:before {
        color: #25517d;
        font-weight: bold;
    }

    @media (min-width: 480px) {
        .rwd-table td:before {
            display: none;
        }

        .rwd-table th,
        .rwd-table td {
            display: table-cell;
            padding: 0.25em 0.5em;
        }

        .rwd-table th:first-child,
        .rwd-table td:first-child {
            padding-left: 0;
        }

        .rwd-table th:last-child,
        .rwd-table td:last-child {
            padding-right: 0;
        }

        .rwd-table th,
        .rwd-table td {
            padding: 1em !important;
        }
    }
</style>

<script>
    /*  ======================== 參考 ================================
        動態增加刪除列 http://www.manongjc.com/article/439.html
        區塊卷軸+凍結表格效果  https://codepen.io/Tiya_blank/pen/XJjzeg
    */
    function findObj(theObj, theDoc){ 
        var p, i, foundObj;
        if(!theDoc) theDoc = document; 
        if( (p = theObj.indexOf("?")) > 0 && parent.frames.length) 
        {
            theDoc = parent.frames[theObj.substring(p+1)].document;
            theObj = theObj.substring(0,p); 
        } 
        if(!(foundObj = theDoc[theObj]) && theDoc.all) 
            foundObj = theDoc.all[theObj]; 
        for (i=0; !foundObj && i < theDoc.forms.length; i++)   
            foundObj = theDoc.forms[i][theObj]; 
        for(i=0; !foundObj && theDoc.layers && i < theDoc.layers.length; i++)   
            foundObj = findObj(theObj,theDoc.layers[i].document); 
        if(!foundObj && document.getElementById) 
            foundObj = document.getElementById(theObj);
        return foundObj;
    }
    //增加列
    function AddRow() { 
        var txtTRLastIndex = findObj("txtTRLastIndex",document); //讀取最後一列，暫存在 txtTRLastIndex
        var rowID = parseInt(txtTRLastIndex.value);
        var addTable = findObj("product-table",document);
        var newTR = addTable.rows.length;

        var data = '<tr class="table-row" id="table-row-'+rowID+'">' +
            '<td data-th="操作"><a class="btn btn-xs btn-success createrow" id="add-more" onClick="AddRow();" title="新增"><i class="fa fa-arrow-right"></i></a> ' +
            '<a class="btn btn-xs btn-danger removerow" onclick="deleteRecord('+rowID+');" title="刪除"><i class="fa fa-times"></i></a></td>' +
            '<td data-th="序">'+newTR+'</td>' +
            //'<td data-th="產品編號" contenteditable="true" onBlur="saveToDatabase(this,\'post_title\',\'<?php //echo $posts[$k]["id"]; ?>\')" onClick="editRow(this);"><?php //echo $posts[$k]["post_title"]; ?>AAB0172500115126</td>' +
            '<td data-th="產品編號" id="p-number-'+rowID+'">系統自動產生</td>' +
            '<td data-th="品項名稱"><input type="hidden" name="pid[]" value=""><input type="hidden" name="redid[]" value=""><input type="text" class="form-control" name="p_name[]" placeholder="輸入品名" required="required" style="width: 200px;"></td>' +
            '<td data-th="分類"><select class="form-control" name="category[]">'+ '{!! $extradata["options"] !!}' +'</select></td>' +
            '<td data-th="數量"><input type="number" class="form-control" name="quantity[]" id="quantity-'+rowID+'" placeholder="數量" required="required" onChange="sumPrice('+rowID+')" min="0" value="" style="width:100px;"></td>' +
            '<td data-th="成本價"><input type="number" class="form-control" name="costprice[]" id="cost-price-'+rowID+'" placeholder="成本價" required="required" onChange="sumPrice('+rowID+')" min="0" value="" style="width:100px;"></td>' +
            //'<td data-th="南台價" style="display:none"><input type="number" class="form-control" name="southprice[]" placeholder="南台價" required="required" min="0" value="0" style="width:100px;"></td>'+
            '<td data-th="售價" style="display:none"><input type="number" class="form-control" name="retailprice[]" placeholder="售價" required="required" min="0" value="0" style="width:100px;"></td>'+
            '<td data-th="業務價"><input type="number" class="form-control" name="salesprice[]" id="sales-price-'+rowID+'" placeholder="業務價" required="required" onChange="sumPrice('+rowID+')" min="0" value="" style="width:100px;"></td>' +
            '<td data-th="成本價金額"><input type="text" class="form-control" name="sumcostprice[]" id="sumcostprice'+rowID+'" onChange="sumPrice('+rowID+')" value="" style="width:130px;"></td>' +
            '<td data-th="業務價金額"><input type="text" class="form-control" name="sumsalesprice[]" id="sumsalesprice'+rowID+'" onChange="sumPrice('+rowID+')" value="" style="width:130px;"></td>' +
            '<td data-th="備註"><textarea class="form-control" name="notes[]" rows="1" placeholder="備註" style="width:100px;"></textarea></td>' +
            '</tr> ';
        $("#table-body").append(data);
        txtTRLastIndex.value = (rowID + 1).toString() ;
        total(); 
    }
    //刪除列
    function deleteRecord(rowid) {
        var deleteTable = findObj("product-table",document);
        var deleteRow = findObj("table-row-" + rowid,document);
        //get Index
        var rowIndex = deleteRow.rowIndex;
        if (deleteTable.rows.length == 2){
            if (confirm("確定要刪除最後一列?")) {
                deleteTable.deleteRow(rowIndex);
            }
        }else{
            //删除指定Index
            deleteTable.deleteRow(rowIndex);
        }

        //更新序號
        for(i = rowIndex; i < deleteTable.rows.length; i++){
            deleteTable.rows[i].cells[1].innerHTML = i.toString();
        }
        total();
    }
    //全部清空，並新增一列
    function ClearAllRow() {
        if (confirm('確定要清空所有進貨商品嗎？')) {
            var clearTable = findObj("product-table",document);
            var rowscount = clearTable.rows.length;
            //delete all row
            for (i = rowscount - 1; i > 0; i--) {
                clearTable.deleteRow(i);
            }
            //reset index=1
            var txtTRLastIndex = findObj("txtTRLastIndex", document);
            txtTRLastIndex.value = "1";
            //add row
            AddRow();
            total();
        }
    }
    //新增多列彈出視窗
    $('#addManyRowModal').on('show.bs.modal', function (event) {
        var modal = $(this)
        // modal.find('.modal-title').text('批量新增')
    })
    //新增多列
    function AddManyRow() {
        var quantity = $('#add-quantity').val();
        for(q = 1; q <= quantity; q++){
            AddRow();
        }
        total();
    }
    //計算金額
    function sumPrice(rowid){
        var sumcostprice = 0;
        var sumsalesprice = 0;
        var costprice = document.getElementById ("cost-price-"+rowid).value;
        var salesprice = document.getElementById ("sales-price-"+rowid).value;
        var quantity = document.getElementById ("quantity-"+rowid).value;

        if(quantity!="" && costprice!=""){
          sumcostprice = parseFloat(quantity) * parseFloat(costprice); 
        }
        if(quantity!="" && salesprice!=""){ 
          sumsalesprice = parseFloat(quantity) * parseFloat(salesprice); 
        }
        document.getElementById ("sumcostprice"+rowid).value = sumcostprice;
        document.getElementById ("sumsalesprice"+rowid).value = sumsalesprice; 
        total();
    }
    //合計數量、金額
    function total(){  
        var totalquantity = 0,totalcostprice = 0,totalsalesprice = 0;
        $("#table-body .table-row input[name='quantity[]']").each(function() {
            if ($(this).val()!=""){
                totalquantity += parseInt($(this).val());
            }
        });
        $("#table-body .table-row input[name='sumcostprice[]']").each(function() {
            if ($(this).val()!=""){
                totalcostprice += parseInt($(this).val());
            }
        });
        $("#table-body .table-row input[name='sumsalesprice[]']").each(function() {
            if ($(this).val()!=""){
                totalsalesprice += parseInt($(this).val());
            }
        });
        $("#total span:eq(0)").text(totalquantity);
        $("#total span:eq(1)").text(totalcostprice);
        $("#total span:eq(2)").text(totalsalesprice);
    }
    total();
</script>