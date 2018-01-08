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
                                    <div class="tb-header-cell" style="width: 130px; left: 33px;">商品編號</div>
                                    <div class="tb-header-cell" style="width: 200px; left: 163px;">商品名</div>
                                    <div class="tb-header-cell" style="width: 60px; left: 363px;">單位</div>
                                    <div class="tb-header-cell" style="width: 60px; left: 423px;">庫存數</div>
                                    <div class="tb-header-cell" style="width: 80px; left: 483px;">進貨數</div>
                                    <div class="tb-header-cell" style="width: 80px; left: 563px;">單價</div>
                                    <div class="tb-header-cell" style="width: 80px; left: 643px;">總價</div>
                                    <div class="tb-header-cell" style="width: 110px; left: 723px;">備註</div>
                                </div>
                            </div>
                            <div class="tb-body" style="top: 30px; height: 320px;">
                                <div class="tb-body-container" style="height: 350px; top: 0px; width: 837px;">
@endif
                                    @foreach($products as $key => $product)
                                    <div role="row" row-id="{{ $product->pid }}" class="tb-row tb-row-{{ $rowEvenOdd[$key%2] }} tb-row-no-animation" style="top: {{ $rowTop += 30 }}px;">
                                        <div tabindex="-1" col-id="isSelected" class="tb-cell tb-cell-no-focus text-left" style="width: 33px; left: 0px; ">
                                            <div class="ui-grid-cell-contents">
                                                <a class="btn btn-xs btn-danger" href="javascript:;" title="刪除"><i class="fa fa-times"></i></a>
                                            </div>
                                        </div>
                                        <div tabindex="-1" class="tb-cell tb-cell-no-focus text-left" style="width: 130px; left: 33px; ">{{ $product->p_number }}</div>
                                        <div tabindex="-1" class="tb-cell tb-cell-no-focus text-left" style="width: 200px; left: 163px; ">
                                            @if($product->p_pic)
                                            <a href="#" role="button" data-toggle="popover" data-container="#receiptdetials" data-placement="bottom" data-html="true" data-content="<img src='{{ rtrim(config('admin.upload.host'), '/').'/'. $product->p_pic }}' width='150px'>">{{ $product->p_name }}</a>
                                            @else {{ $product->p_name }} 
                                            @endif

                                        </div>
                                        <div tabindex="-1" class="tb-cell tb-cell-no-focus text-right" style="width: 60px; left: 363px; ">{{ $product->p_unit }}</div>
                                        <div tabindex="-1" class="tb-cell tb-cell-no-focus text-right" style="width: 60px; left: 423px; ">{{ $product->stock()->where('wid', Admin::user()->wid)->sum('s_stock') }}</div>
                                        
                                        <div tabindex="{{ $tabindex++ }}" class="tb-cell tb-cell-no-focus text-right" style="width: 80px; left: 483px;" col-id="red_quantity" contenteditable="true">1</div>
                                        <div tabindex="{{ $tabindex++ }}" class="tb-cell tb-cell-no-focus text-right" style="width: 80px; left: 563px;" col-id="red_price" contenteditable="true">{{ $product->p_costprice }}</div>
                                        <div tabindex="{{ $tabindex++ }}" class="tb-cell tb-cell-no-focus text-right" style="width: 80px; left: 643px;" col-id="red_amount" contenteditable="true">{{ $product->p_costprice }}</div>
                                        <div tabindex="{{ $tabindex++ }}" class="tb-cell tb-cell-no-focus text-left" style="width: 110px; left: 723px;" col-id="red_notes" contenteditable="true"></div>
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
        <div class="select2-results">
            已選擇：<span>0</span>
        </div>

    </div>
</div>
@endif
<script>
$(function() {

    $('[data-toggle="popover"]').popover({
        trigger: 'hover'
    }); 

    $("#receiptdetials .tb-body div[contenteditable='true']")
    .click(function(){document.execCommand('selectAll',false,null);})
    .change(function() {

    })
    .keypress(function(e) {
        if (e.keyCode == 13) {
            return false;
        }
    });
    // var $quantity, $price, $amount;
    // $("#receiptdetials .tb-body ").on("DOMSubtreeModified", "div[contenteditable='true']", function(){

    //     $quantity = $("#receiptdetials .tb-body div[col-id='red_quantity']").text();
    //     $price = $("#receiptdetials .tb-body div[col-id='red_price']").text();
    //     $amount = $("#receiptdetials .tb-body div[col-id='red_amount']").text();
    // });
    // $("#receiptdetials .tb-body ").on("DOMSubtreeModified", "div[col-id='red_quantity']", function(){

    //     $("#receiptdetials .tb-body div[col-id='red_amount']").text($quantity*$price);
    // });
    // $("#receiptdetials .tb-body ").on("DOMSubtreeModified", "div[col-id='red_price']", function(){

    //     $("#receiptdetials .tb-body div[col-id='red_amount']").text($quantity*$price);
    // });
    // $("#receiptdetials .tb-body ").on("DOMSubtreeModified", "div[col-id='red_amount']", function(){

    //     $("#receiptdetials .tb-body div[col-id='red_price']").text($amount/$quantity);

    // });

});
</script>