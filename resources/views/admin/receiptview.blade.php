<div class="form-group 1" id="receiptdetials">

    <label for="re_amount" class="col-sm-2 control-label"></label>
    <div class="col-sm-12">

        <div class="select2-results footer-btn-mrgin">
            <div class="tb-responsive">

                <div class="fresh" style="width: 854px; height: 350px;">
                    <div class="tb-bl tb-bl-full-height tb-layout-normal tb-ltr">

                        <div class="tb-root tb-font-style" role="grid">
                            <div class="tb-header" role="row" style="height: 30px;">
                                <div class="tb-header-container">
                                    <div class="tb-header-cell" style="width: 130px; left: 0px;">商品編號</div>
                                    <div class="tb-header-cell" style="width: 200px; left: 130px;">商品名</div>
                                    <div class="tb-header-cell" style="width: 60px; left: 330px;">單位</div>
                                    <div class="tb-header-cell" style="width: 80px; left: 390px;">進貨數</div>
                                    <div class="tb-header-cell" style="width: 80px; left: 470px;">單價</div>
                                    <div class="tb-header-cell" style="width: 80px; left: 550px;">總價</div>
                                    <div class="tb-header-cell" style="width: 200px; left: 630px;">備註</div>
                                </div>
                            </div>
                            <div class="tb-body" style="top: 30px; height: 320px;">
                                <div class="tb-body-container" style="height: 350px; top: 0px; width: 837px;">
                                    @foreach($receiptdetails as $key => $receiptdetail)
                                    <div role="row" class="tb-row tb-row-{{ $rowEvenOdd[$key%2] }} tb-row-no-animation" style="top: {{ $rowTop += 30 }}px;">
                                        <div tabindex="-1" class="tb-cell tb-cell-no-focus text-left" style="width: 130px; left: 0px; ">{{ $products[$key]['p_number'] }}</div>
                                        <div tabindex="-1" class="tb-cell tb-cell-no-focus text-left" style="width: 200px; left: 130px; ">
                                            @if($products[$key]['p_pic'])
                                            <a href="#" role="button" data-toggle="popover" data-container="#receiptdetials" data-placement="bottom" data-html="true" data-content="<img src='{{ rtrim(config('admin.upload.host'), '/').'/'. $products[$key]['p_pic'] }}' width='150px'>">{{ $products[$key]['p_name'] }}</a>
                                            @else {{ $products[$key]['p_name'] }} 
                                            @endif

                                        </div>
                                        <div tabindex="-1" class="tb-cell tb-cell-no-focus text-right" style="width: 60px; left: 330px; ">{{ $products[$key]['p_unit'] }}</div>

                                        <div tabindex="-1" col-id="red_quantity" class="tb-cell tb-cell-no-focus text-right" style="width: 80px; left: 390px;">{{ $receiptdetail->red_quantity }}</div>
                                        <div tabindex="-1" col-id="red_price" class="tb-cell tb-cell-no-focus text-right" style="width: 80px; left: 470px;">{{ $receiptdetail->red_price }}</div>
                                        <div tabindex="-1" col-id="red_amount" class="tb-cell tb-cell-no-focus text-right" style="width: 80px; left: 550px;">{{ $receiptdetail->red_amount }}</div>
                                        <div tabindex="-1" class="tb-cell tb-cell-no-focus text-left" style="width: 200px; left: 630px;">{{ $receiptdetail->red_notes }}</div>
                                    </div>
                                    @endforeach
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
    countTotal();
    $('[data-toggle="popover"]').popover({
        trigger: 'hover'
    });

});
function countTotal(){
    var total = 0,quantity = 0;

    $("#receiptdetials .tb-body-container div[col-id='red_amount']").each(function() {
        total += parseInt($(this).text());
    });
    $("#receiptdetials .tb-body-container div[col-id='red_quantity']").each(function() {
        quantity += parseInt($(this).text());
    });
    $("#receiptdetials .search-footer span:first").text(quantity);
    $("#receiptdetials .search-footer span:last").text(total);
}
</script>