<ul class="list-group">
    @foreach($details as $detail)
    <li class="list-group-item countinglist" data-toggle="modal" data-target="#countingmodal" data-indid="{{ $detail->indid }}">{{ $detail->p_name }} 
        @if(empty($detail->ind_at))
        <span class="pull-right glyphicon glyphicon-exclamation-sign" style="color:#00A662;font-size:20px;"></span>
        @else
        <span class="pull-right">已盤點</span>
        @endif
    </li>
    @endforeach
</ul>






<div class="modal fade" id="countingmodal">
<div class="modal-dialog">
    <div class="modal-content" style="text-align:center;">
        <div class="modal-body">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"> &times; </button>
            <div class="pull-left product-img" style="width:50%;min-height:200px;">
                
            </div>
            <form action="" method="post" accept-charset="UTF-8" class="form-horizontal" pjax-container>
                <div class="pull-left" style="width:50%;min-height:200px;">
                    <input type="hidden" name="indid" value="">
                    <h3 style="text-align:center;"></h3>
                    <p style="text-align:left;margin-left:25%;margin-top:20px;">款式：<span id="ind_type"></span></p>
                    <p style="text-align:left;margin-left:25%;margin-top:20px;">目前庫存：<span id="ind_stock"></span></p>
                    <p style="text-align:left;margin-left:25%;margin-top:20px;">盤點數量：<input type="text" name="ind_quantity" style="max-width:60px;"></p>
                    {{ method_field('PUT') }}
                    {{ csrf_field() }}
                    <input type="submit" id="okButton" class="btn" style="margin-top:10px;display:none;" value="確定">
                </div>
            </form>
        </div>
    </div>

    <div class="loading-fullpage loading-container" style="display: none;">
        <div class="loading-error" style="display: none;">
            <h3 style="line-height:80px;font-weight:600;"><span class="glyphicon glyphicon-remove" style="color:#dd4b39"></span> 網頁發生錯誤</h3>
            <button class='btn btn-default' data-dismiss="modal" aria-hidden="true">關閉</button>
        </div>
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


</div>
</div>


<script>
$('#okButton').on('click',  function () {
    $('.loading-container').show();
    $('.modal-backdrop').remove();
    $('body').removeClass('modal-open').css('padding-right','0');
});

$('#countingmodal').on('show.bs.modal',  function (event) {
    var button = $(event.relatedTarget);
    var recipient = button.data('indid');
    var modal = $(this);

    $.get('/admin/inventorydetails/getdata/' + recipient, function(data) {
        /**
         * data：
         * [0]indid|[1]商品名|[2]src|[3]款式|[4]目前庫存|[5]盤點數|[6]已盤點Boolean|[7]是盤點人Boolean
         */
        data = data.split("|");
        modal.find('input[name="indid"]').val(data[0]);
        modal.find('form').attr('action',"/admin/inventorydetails/" + data[0]);
        modal.find('h3').text(data[1]);
        if (data[2]) {
            modal.find('.product-img').html('<img src="/upload/'+ data[2] +'" alt="'+ data[1] +'" style="width:100%;">');
        }else{
            modal.find('.product-img').html('<span class="glyphicon glyphicon-picture" style="font-size: 20vmin;"></span>');
        }
        modal.find('#ind_type').text(data[3]);
        modal.find('#ind_stock').text(data[4]);
        modal.find('input[name="ind_quantity"]').val(data[5]);

        var okButton = $('#okButton');
        
        if(data[6] == '1' && data[7] != '1'){
            //不是原盤點人不可修改
            okButton.removeClass("btn-success").addClass("btn-default").val("已盤點");
            okButton.attr('disabled', true);
        }else{
            okButton.removeClass("btn-default").addClass("btn-success").val("確定");
            okButton.attr('disabled', false);
        }
        
        okButton.show();
    });
    $('#countingmodal').on('hidden.bs.modal', function (event) {
        $('.product-img').html('');
        $('#okButton').hide();        
    });


});
</script>