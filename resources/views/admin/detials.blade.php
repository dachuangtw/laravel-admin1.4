@if($firsttime)
<div class="form-group 1" id="detials">

    <label for="re_amount" class="col-sm-2 control-label"></label>
    <div class="col-sm-8">

        <div class="select2-results footer-btn-mrgin">
            <div class="tb-responsive">

                <div class="fresh" style="width: 854px; height: 350px;">
                    <div class="tb-bl tb-bl-full-height tb-layout-normal tb-ltr">

                        <div class="tb-root tb-font-style" role="grid">
                            <div class="tb-header" role="row" style="height: 30px;">
                                <div class="tb-header-container">
                                    @foreach($rowTitle as $key => $title)
                                    <div class="tb-header-cell" style="width: {{ $rowWidth[$key] }}px; left: {{ $rowLeft[$key] }}px;">{{ $title }}</div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="tb-body" style="top: 30px; height: 320px;">
                                <div class="tb-body-container" style="height: 350px; top: 0px; width: 837px;">
                                    @if($action == 'create')
                                    <input type="hidden" name="action" id="action" value="create">
                                    @elseif($action == 'edit')
                                    <input type="hidden" name="action" id="action" value="edit">
                                    @elseif($action == 'editadd')
                                    <input type="hidden" name="action" id="action" value="edit">
                                    @endif

@endif



                                    <!-- 新建 -->
                                    @if($action == 'create')
                                    @foreach($products as $key => $product)
                                    <div role="row" row-id="{{ $product->pid }}" class="tb-row tb-row-{{ $rowEvenOdd[$key%2] }} tb-row-no-animation" style="top: {{ $rowTop += 30 }}px;">
                                        <input type="hidden" name="pid[]" value="{{ $product->pid }}">
                                        <div tabindex="-1" col-id="isSelected" class="tb-cell tb-cell-no-focus text-left" style="width: {{ $rowWidth[0] }}px; left: {{ $rowLeft[0] }}px; ">
                                            <div class="ui-grid-cell-contents">
                                                <a class="btn btn-xs btn-danger removerow" href="javascript:;" title="刪除"><i class="fa fa-times"></i></a>
                                            </div>
                                        </div>
                                        <div tabindex="-1" class="tb-cell tb-cell-no-focus text-left" style="width: {{ $rowWidth[1] }}px; left: {{ $rowLeft[1] }}px; ">{{ $product->p_number }}</div>
                                        <div tabindex="-1" class="tb-cell tb-cell-no-focus text-left" style="width: {{ $rowWidth[2] }}px; left: {{ $rowLeft[2] }}px; ">
                                            @if($product->p_pic)
                                            <a href="#" role="button" data-toggle="popover" data-container="#detials" data-placement="bottom" data-html="true" data-content="<img src='{{ rtrim(config('admin.upload.host'), '/').'/'. $product->p_pic }}' width='150px'>">{{ $product->p_name }}</a>
                                            @else {{ $product->p_name }} 
                                            @endif

                                        </div>
                                        <div tabindex="-1" class="tb-cell tb-cell-no-focus text-right" style="width: {{ $rowWidth[3] }}px; left: {{ $rowLeft[3] }}px; ">{{ $product->p_unit }}</div>
                                        <div tabindex="-1" class="tb-cell tb-cell-no-focus text-right" style="width: {{ $rowWidth[4] }}px; left: {{ $rowLeft[4] }}px; ">{{ $product->stock()->where('wid', Admin::user()->wid)->sum('st_stock') }}</div>
                                        <div tabindex="-1" class="tb-cell tb-cell-no-focus text-right" style="width: {{ $rowWidth[5] }}px; left: {{ $rowLeft[5] }}px; ">
                                            @if(!empty($stock[$key]))
                                                @if(count($stock[$key]) == 1 && $val = $stock[$key][0])
                                                <input type="hidden" name="stid[]" value="{{ $val['stid'] }}">
                                                {{ $val['st_type'] }}
                                                @else
                                                <select name="stid[]">
                                                    @foreach($stock[$key] as $val)
                                                        <option value="{{ $val['stid'] }}">{{ $val['st_type'] }}</option>
                                                    @endforeach
                                                </select>
                                                @endif
                                            @else
                                                <input type="hidden" name="stid[]" value="">
                                                不分款
                                            @endif
                                        
                                        </div>
                                        <div tabindex="-1" class="tb-cell tb-cell-no-focus text-right" style="width: {{ $rowWidth[6] }}px; left: {{ $rowLeft[6] }}px; ">
                                            <input type="text" name="quantity[]" value="1"></div>
                                        <div tabindex="-1" class="tb-cell tb-cell-no-focus text-right" style="width: {{ $rowWidth[7] }}px; left: {{ $rowLeft[7] }}px; ">
                                            <input type="text" name="price[]" value="{{ $product->$showprice }}"></div>
                                        <div tabindex="-1" class="tb-cell tb-cell-no-focus text-right" style="width: {{ $rowWidth[8] }}px; left: {{ $rowLeft[8] }}px; ">
                                            <input type="text" name="amount[]" value="{{ $product->$showprice }}"></div>
                                        <div tabindex="-1" class="tb-cell tb-cell-no-focus text-left" style="width: {{ $rowWidth[9] }}px; left: {{ $rowLeft[9] }}px; ">
                                            <input type="text" name="notes[]" value=""></div>

                                    </div>
                                    @endforeach
                                    @endif


                                    <!-- 編輯 -->
                                    @if($action == 'edit')
                                    @foreach($savedDetails as $key => $savedDetail)
                                    <div role="row" class="tb-row tb-row-{{ $rowEvenOdd[$key%2] }} tb-row-no-animation" style="top: {{ $rowTop += 30 }}px;">
                                        <input type="hidden" name="pid[]" value="{{ $products[$key]['pid'] }}">
                                        <input type="hidden" name="redid[]" value="{{ $savedDetail->redid }}">
                                        <div tabindex="-1" col-id="isSelected" class="tb-cell tb-cell-no-focus text-left" style="width: {{ $rowWidth[0] }}px; left: {{ $rowLeft[0] }}px; ">
                                            <div class="ui-grid-cell-contents">
                                                <a class="btn btn-xs btn-danger removerow" href="javascript:;" title="刪除"><i class="fa fa-times"></i></a>
                                            </div>
                                        </div>
                                        <div tabindex="-1" class="tb-cell tb-cell-no-focus text-left" style="width: {{ $rowWidth[1] }}px; left: {{ $rowLeft[1] }}px; ">{{ $products[$key]['p_number'] }}</div>
                                        <div tabindex="-1" class="tb-cell tb-cell-no-focus text-left" style="width: {{ $rowWidth[2] }}px; left: {{ $rowLeft[2] }}px; ">
                                            @if($products[$key]['p_pic'])
                                            <a href="#" role="button" data-toggle="popover" data-container="#receiptdetials" data-placement="bottom" data-html="true" data-content="<img src='{{ rtrim(config('admin.upload.host'), '/').'/'. $products[$key]['p_pic'] }}' width='150px'>">{{ $products[$key]['p_name'] }}</a>
                                            @else {{ $products[$key]['p_name'] }} 
                                            @endif
                                        </div>
                                        <div tabindex="-1" class="tb-cell tb-cell-no-focus text-right" style="width: {{ $rowWidth[3] }}px; left: {{ $rowLeft[3] }}px; ">{{ $products[$key]['p_unit'] }}</div>                                        
                                        <div tabindex="-1" class="tb-cell tb-cell-no-focus text-right" style="width: {{ $rowWidth[4] }}px; left: {{ $rowLeft[4] }}px; ">
                                            @if(isset($stock[$savedDetail->stid]))
                                                @if(empty($savedDetail->redid))
                                                <select name="stid[]">
                                                    @foreach($stock as $val)
                                                        <option value="{{ $val['stid'] }}">{{ $val['st_type'] }}</option>
                                                    @endforeach
                                                </select>
                                                @else                                            
                                                <input type="hidden" name="stid[]" value="{{ $savedDetail->stid }}">
                                                {{ $stock[$savedDetail->stid] }}
                                                @endif
                                            @else
                                                <input type="hidden" name="stid[]" value="{{$savedDetail->stid}}">
                                                不分款
                                            @endif
                                        
                                        </div>
                                        <div tabindex="-1" class="tb-cell tb-cell-no-focus text-right" style="width: {{ $rowWidth[5] }}px; left: {{ $rowLeft[5] }}px; ">
                                            <input type="text" name="quantity[]" value="{{ $savedDetail->red_quantity }}"></div>
                                        <div tabindex="-1" class="tb-cell tb-cell-no-focus text-right" style="width: {{ $rowWidth[6] }}px; left: {{ $rowLeft[6] }}px; ">
                                            <input type="text" name="price[]" value="{{ $savedDetail->red_price }}"></div>
                                        <div tabindex="-1" class="tb-cell tb-cell-no-focus text-right" style="width: {{ $rowWidth[7] }}px; left: {{ $rowLeft[7] }}px; ">
                                            <input type="text" name="amount[]" value="{{ $savedDetail->red_amount }}"></div>
                                        <div tabindex="-1" class="tb-cell tb-cell-no-focus text-left" style="width: {{ $rowWidth[8] }}px; left: {{ $rowLeft[8] }}px; ">
                                            <input type="text" name="notes[]" value="{{ $savedDetail->red_notes }}"></div>

                                    </div>
                                    @endforeach
                                    @endif

                                    <!-- 編輯-新增明細 -->
                                    @if($action == 'editadd')
                                    @foreach($products as $key => $product)
                                    <div role="row" class="tb-row tb-row-{{ $rowEvenOdd[$key%2] }} tb-row-no-animation" style="top: {{ $rowTop += 30 }}px;">
                                        <input type="hidden" name="pid[]" value="{{ $product->pid }}">
                                        <input type="hidden" name="redid[]" value="">
                                        <div tabindex="-1" col-id="isSelected" class="tb-cell tb-cell-no-focus text-left" style="width: {{ $rowWidth[0] }}px; left: {{ $rowLeft[0] }}px; ">
                                            <div class="ui-grid-cell-contents">
                                                <a class="btn btn-xs btn-danger removerow" href="javascript:;" title="刪除"><i class="fa fa-times"></i></a>
                                            </div>
                                        </div>
                                        <div tabindex="-1" class="tb-cell tb-cell-no-focus text-left" style="width: {{ $rowWidth[1] }}px; left: {{ $rowLeft[1] }}px; ">{{ $product->p_number }}</div>
                                        <div tabindex="-1" class="tb-cell tb-cell-no-focus text-left" style="width: {{ $rowWidth[2] }}px; left: {{ $rowLeft[2] }}px; ">
                                            @if($product->p_pic)
                                            <a href="#" role="button" data-toggle="popover" data-container="#receiptdetials" data-placement="bottom" data-html="true" data-content="<img src='{{ rtrim(config('admin.upload.host'), '/').'/'. $product->p_pic }}' width='150px'>">{{ $product->p_name }}</a>
                                            @else {{ $product->p_name }} 
                                            @endif
                                        </div>
                                        <div tabindex="-1" class="tb-cell tb-cell-no-focus text-right" style="width: {{ $rowWidth[3] }}px; left: {{ $rowLeft[3] }}px; ">{{ $product->p_unit }}</div>
                                        <div tabindex="-1" class="tb-cell tb-cell-no-focus text-right" style="width: {{ $rowWidth[4] }}px; left: {{ $rowLeft[4] }}px; ">
                                            @if(!empty($stock[$key]))
                                            <select name="stid[]">
                                                @foreach($stock[$key] as $val)
                                                    <option value="{{ $val['stid'] }}">{{ $val['st_type'] }}</option>
                                                @endforeach
                                            </select>
                                            @else
                                                <input type="hidden" name="stid[]" value="">
                                                不分款
                                            @endif
                                        
                                        </div>
                                        <div tabindex="-1" class="tb-cell tb-cell-no-focus text-right" style="width: {{ $rowWidth[5] }}px; left: {{ $rowLeft[5] }}px; ">
                                            <input type="text" name="quantity[]" value="1"></div>
                                        <div tabindex="-1" class="tb-cell tb-cell-no-focus text-right" style="width: {{ $rowWidth[6] }}px; left: {{ $rowLeft[6] }}px; ">
                                            <input type="text" name="price[]" value="{{ $product->$showprice }}"></div>
                                        <div tabindex="-1" class="tb-cell tb-cell-no-focus text-right" style="width: {{ $rowWidth[7] }}px; left: {{ $rowLeft[7] }}px; ">
                                            <input type="text" name="amount[]" value="{{ $product->$showprice }}"></div>
                                        <div tabindex="-1" class="tb-cell tb-cell-no-focus text-left" style="width: {{ $rowWidth[8] }}px; left: {{ $rowLeft[8] }}px; ">
                                            <input type="text" name="notes[]" value=""></div>

                                    </div>
                                    @endforeach
                                    @endif

                                    @if($firsttime)
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="select2-results search-footer">
            合計數量：<span>0</span>&nbsp;&nbsp;&nbsp;&nbsp;合計金額：<span>0</span> 元
        </div>

    </div>
</div>
<script>
$(function() {
    var quantity = 0,
        price = 0,
        amount = 0;
    $(document).on("focus","#detials .tb-cell input[type='text']",function(){
        this.select();
    });
    $(document).on("keypress","#detials .tb-cell input[type='text']",function(e){
        if (e.keyCode == 13) {
            $(this).blur();
            return false;
        }
    });



    $(document).on("change","#detials .tb-cell input[name='quantity[]']",function(){
        quantity = $(this).val();
        price = $(this).parent().next().find("input[name='price[]']").val();
        $(this).parent().next().next().find("input[name='amount[]']").val(quantity * price);

        countTotal();
    });
    $(document).on("change","#detials .tb-cell input[name='price[]']",function(){
        price = $(this).val();
        quantity = $(this).parent().prev().find("input[name='quantity[]']").val();
        $(this).parent().next().find("input[name='amount[]']").val(quantity * price);

        countTotal();
    });
    $(document).on("change","#detials .tb-cell input[name='amount[]']",function(){
        amount = $(this).val();
        quantity = $(this).parent().prev().prev().find("input[name='quantity[]']").val();
        var tempamount = amount / quantity;
        tempamount = Math.floor(tempamount * 100) / 100;
        $(this).parent().prev().find("input[name='price[]']").val(tempamount);

        countTotal();
    });
    $(document).on("click","a.removerow",function(){
        $(this).parent().parent().parent().remove();
        var top = -30;
        $("#detials .tb-row").each(function(){
            $(this).css("top",top +=30);

        });
        $("#detials .tb-row:even").removeClass("tb-row-odd").addClass("tb-row-even");
        $("#detials .tb-row:odd").removeClass("tb-row-even").addClass("tb-row-odd");
        countTotal();
    });
});
function countTotal(){
    var total = 0,quantity = 0;

    $("#detials .tb-cell input[name='amount[]']").each(function() {
        total += parseInt($(this).val());
    });
    $("#detials .tb-cell input[name='quantity[]']").each(function() {
        quantity += parseInt($(this).val());
    });
    $("#detials .search-footer span:first").text(quantity);
    $("#detials .search-footer span:last").text(total);
}
</script>
@endif
<script>
$(function() {
    countTotal();
    $('[data-toggle="popover"]').popover({
        trigger: 'hover'
    });
});
</script>