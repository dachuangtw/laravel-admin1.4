<ul class="list-group">
    <li class="list-group-item countinglist" data-toggle="modal" data-target="#countingmodal" data-indid="1">動物牙膏筆袋 <span class="pull-right glyphicon glyphicon-exclamation-sign" style="color:#00A662;font-size:20px;"></span></li>
    <li class="list-group-item countinglist" data-toggle="modal" data-target="#countingmodal" data-indid="2">動物牙膏筆袋 <span class="pull-right">已盤點</span></li>
</ul>

<div class="modal fade" id="countingmodal">
<div class="modal-dialog">
    <div class="modal-content" style="text-align:center;">
        <div class="modal-body">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"> &times; </button>
            <form action="" method="post">
                <input type="hidden" name="indid" value="">
                <div class="pull-left" style="width:50%;">
                    <img src="http://localhost/upload/image/dfd93767e8bd4a082e1e652fcacb08db.jpeg" alt="" style="max-width:100%;">
                </div>
                <div class="pull-left" style="width:50%;height:auto;">
                    <h3 style="text-align:center;">動物牙膏筆袋</h3>
                    <p style="text-align:left;margin-left:25%;margin-top:20px;">款式：<span id="in_type">不分款</span></p>
                    <p style="text-align:left;margin-left:25%;margin-top:20px;">目前庫存：<span id="in_stock">20</span></p>
                    <p style="text-align:left;margin-left:25%;margin-top:20px;">盤點數量：<input type="text" name="in_quantity" style="max-width:80px;"></p>
                    <input type="submit" class="btn btn-success" style="margin-top:10px;" value="確定">
                </div>
            </form>
        </div>
    </div>
</div>
</div>
<script>

$('#countingmodal').on('show.bs.modal',  function (event) {
    var button = $(event.relatedTarget);
    var recipient = button.data('indid');
    var modal = $(this);

    $.get('admin/getstockdata/' + recipient, function(data) {
        /**
         * data：
         * [0]indid|[1]商品名|[2]src|[3]款式|[4]目前庫存|[5]盤點數|[6]已盤點Boolean|[7]是盤點人Boolean
         */
        data = data.split("|");
        modal.find('input[name="indid"]').val(data[0]);
        modal.find('h3').text(data[1]);
        modal.find('img').attr('alt',data[1]).attr('src',data[2]);
        modal.find('#in_type').text(data[3]);
        modal.find('#in_stock').text(data[4]);
        modal.find('input[name="in_quantity"]').val(data[5]);

        var submitButton = modal.find('input[type="submit"]');
        if(data[6]){
            submitButton.class("btn btn-default").val("已盤點");
            if(data[7]){
                submitButton.attr('disabled', false);
            }else{ //不是原盤點人不可修改
                submitButton.attr('disabled', true);
            }
        }else{
            submitButton.class("btn btn-success").val("確定").attr('disabled', true);
        }
    });


});
</script>