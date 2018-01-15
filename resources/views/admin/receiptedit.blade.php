@if($firsttime)
<div class="form-group 1" id="receiptdetials">

    <label for="re_amount" class="col-sm-2 control-label"></label>
    <div class="col-sm-8">

        <div class="select2-results footer-btn-mrgin">
            <div class="tb-responsive">

                <div class="fresh" style="width: 854px; height: 350px;">
                    <div class="tb-bl tb-bl-full-height tb-layout-normal tb-ltr">

                        <div class="tb-root tb-font-style" role="grid">
                            <div class="tb-header" role="row" style="height: 30px;">
                                <div class="tb-header-container">
                                    <div class="tb-header-cell" style="width: 33px; left: 0px;">
                                    </div>
                                    <div class="tb-header-cell" style="width: 100px; left: 33px;">商品編號</div>
                                    <div class="tb-header-cell" style="width: 150px; left: 133px;">商品名</div>
                                    <div class="tb-header-cell" style="width: 60px; left: 283px;">單位</div>
                                    <div class="tb-header-cell" style="width: 80px; left: 343px;">款式</div>
                                    <div class="tb-header-cell" style="width: 80px; left: 423px;">進貨數</div>
                                    <div class="tb-header-cell" style="width: 80px; left: 503px;">單價</div>
                                    <div class="tb-header-cell" style="width: 80px; left: 583px;">總價</div>
                                    <div class="tb-header-cell" style="width: 110px; left: 693px;">備註</div>
                                </div>
                            </div>
                            <div class="tb-body" style="top: 30px; height: 320px;">
                                <div class="tb-body-container" style="height: 350px; top: 0px; width: 837px;">
                                <input type="hidden" name="action" id="action" value="edit">
                                @endif
                                    @foreach($receiptdetails as $key => $receiptdetail)
                                    <div role="row" class="tb-row tb-row-{{ $rowEvenOdd[$key%2] }} tb-row-no-animation" style="top: {{ $rowTop += 30 }}px;">
                                        <input type="hidden" name="pid[]" value="{{ $products[$key]['pid'] }}">
                                        <input type="hidden" name="redid[]" value="{{ $receiptdetail->redid }}">
                                        <div tabindex="-1" col-id="isSelected" class="tb-cell tb-cell-no-focus text-left" style="width: 33px; left: 0px; ">
                                            <div class="ui-grid-cell-contents">
                                                <a class="btn btn-xs btn-danger removerow" href="javascript:;" title="刪除"><i class="fa fa-times"></i></a>
                                            </div>
                                        </div>
                                        <div tabindex="-1" class="tb-cell tb-cell-no-focus text-left" style="width: 100px; left: 33px; ">{{ $products[$key]['p_number'] }}</div>
                                        <div tabindex="-1" class="tb-cell tb-cell-no-focus text-left" style="width: 150px; left: 133px; ">
                                            @if($products[$key]['p_pic'])
                                            <a href="#" role="button" data-toggle="popover" data-container="#receiptdetials" data-placement="bottom" data-html="true" data-content="<img src='{{ rtrim(config('admin.upload.host'), '/').'/'. $products[$key]['p_pic'] }}' width='150px'>">{{ $products[$key]['p_name'] }}</a>
                                            @else {{ $products[$key]['p_name'] }} 
                                            @endif
                                        </div>
                                        <div tabindex="-1" class="tb-cell tb-cell-no-focus text-right" style="width: 60px; left: 283px; ">{{ $products[$key]['p_unit'] }}</div>
                                        <div tabindex="-1" class="tb-cell tb-cell-no-focus text-right" style="width: 80px; left: 343px;">
                                            @if(!empty($stock[$key]) && empty($receiptdetail->redid))
                                            <select name="stid[]">
                                                @foreach($stock[$key] as $val)
                                                    <option value="{{ $val['stid'] }}">{{ $val['st_type'] }}</option>
                                                @endforeach
                                            </select>
                                            @elseif(!empty($stock[$receiptdetail->stid]) && !empty($receiptdetail->redid))
                                                <input type="hidden" name="stid[]" value="{{ $receiptdetail->stid }}">
                                                {{ $stock[$receiptdetail->stid]['st_type'] }}
                                            @else
                                                <input type="hidden" name="stid[]" value="new">
                                                不分款
                                            @endif
                                        
                                        </div>
                                        <div tabindex="-1" class="tb-cell tb-cell-no-focus text-right" style="width: 80px; left: 423px;"><input type="text" name="red_quantity[]" value="{{ $receiptdetail->red_quantity }}"></div>
                                        <div tabindex="-1" class="tb-cell tb-cell-no-focus text-right" style="width: 80px; left: 503px;"><input type="text" name="red_price[]" value="{{ $receiptdetail->red_price }}"></div>
                                        <div tabindex="-1" class="tb-cell tb-cell-no-focus text-right" style="width: 80px; left: 583px;"><input type="text" name="red_amount[]" value="{{ $receiptdetail->red_amount }}"></div>
                                        <div tabindex="-1" class="tb-cell tb-cell-no-focus text-left" style="width: 110px; left: 693px;"><input type="text" name="red_notes[]" value="{{ $receiptdetail->red_notes }}"></div>

                                    </div>
                                    @endforeach
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
@endif
<script>
$(function() {
    countTotal();
    $('[data-toggle="popover"]').popover({
        trigger: 'hover'
    });
    var quantity = 0,
        price = 0,
        amount = 0;

    $("#receiptdetials .tb-cell input[type='text']")
        .focus(function() {
            this.select();
        })
        .keypress(function(e) {
            if (e.keyCode == 13) {
                $(this).blur();
                return false;
            }
        });

    $("#receiptdetials .tb-cell input[name='red_quantity[]']")
        .change(function() {
            quantity = $(this).val();
            price = $(this).parent().next().find("input[name='red_price[]']").val();
            $(this).parent().next().next().find("input[name='red_amount[]']").val(quantity * price);

            countTotal();
        });
    $("#receiptdetials .tb-cell input[name='red_price[]']")
        .change(function() {
            price = $(this).val();
            quantity = $(this).parent().prev().find("input[name='red_quantity[]']").val();
            $(this).parent().next().find("input[name='red_amount[]']").val(quantity * price);

            countTotal();
        });
    $("#receiptdetials .tb-cell input[name='red_amount[]']")
        .change(function() {
            amount = $(this).val();
            quantity = $(this).parent().prev().prev().find("input[name='red_quantity[]']").val();
            var tempamount = amount / quantity;
            tempamount = Math.floor(tempamount * 100) / 100;
            $(this).parent().prev().find("input[name='red_price[]']").val(tempamount);

            countTotal();
        });

    $("a.removerow").click(function(){
        $(this).parent().parent().parent().remove();
        var top = -30;
        $("#receiptdetials .tb-row").each(function(){
            $(this).css("top",top +=30);

        });
        $("#receiptdetials .tb-row:even").removeClass("tb-row-odd").addClass("tb-row-even");
        $("#receiptdetials .tb-row:odd").removeClass("tb-row-even").addClass("tb-row-odd");
        countTotal();
    });

});
function countTotal(){
    var total = 0,quantity = 0;

    $("#receiptdetials .tb-cell input[name='red_amount[]']").each(function() {
        total += parseInt($(this).val());
    });
    $("#receiptdetials .tb-cell input[name='red_quantity[]']").each(function() {
        quantity += parseInt($(this).val());
    });
    $("#receiptdetials .search-footer span:first").text(quantity);
    $("#receiptdetials .search-footer span:last").text(total);
}
</script>