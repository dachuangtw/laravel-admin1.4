@if($target=='product')

<div class='modal fade' id="selectproduct">
    <div class='modal-dialog modal-lg'>
        <div class='modal-content'>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"> &times; </button>
                <h4 class="title">商品資料 </h4>
            </div>
            <div class='modal-body'>
                <div class="select2-search">
                    <div class="flex-row">
                        <div class="flex-col-xs no-padding padding-right-10">
                            <div class="input-group">
                                <input class="form-control no-border-right" placeholder="請輸入搜尋的商品名 或 商品編號" type="text" id="searchtext">
                                <span class="input-group-btn">
                                <button class="btn btn-transparent-grey2" type="button" id="searchbutton"> <i class="fa fa-search"></i> </button>
                                </span>
                            </div>
                        </div>
                        <button class="btn btn-sm btn-default" id="searchall"> 最新 </button>
                        <button class="btn btn-sm btn-primary" id="searchselected"> 已選 </button>
                    </div>
                </div>
                <div class="select2-results footer-btn-mrgin">
                    <div class="tb-responsive">

                        <div class="fresh" style="width: 100%; height: 350px;">
                            <div class="tb-bl tb-bl-full-height tb-layout-normal tb-ltr">

                                <div class="tb-root tb-font-style" role="grid">
                                    <div class="tb-header" role="row" style="height: 30px;">
                                        <div class="tb-header-container">
                                            <div class="tb-header-cell" style="width: 33px; left: 0px;">
                                            </div>
                                            <div class="tb-header-cell" style="width: 130px; left: 33px;">商品編號</div>
                                            <div class="tb-header-cell" style="width: 300px; left: 163px;">商品名</div>
                                            <div class="tb-header-cell" style="width: 80px; left: 463px;">單位</div>
                                            <div class="tb-header-cell" style="width: 80px; left: 543px;">庫存數</div>
                                        </div>
                                    </div>
                                    <div class="tb-body" style="top: 30px; height: 320px;">
                                        <div class="tb-body-container" style="height: 350px; top: 0px; width: 837px;">
                                        
                                            
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
            <div class="modal-footer">
                <button class="btn btn-sm btn-success margin-5" id="addsubmit"> 加入 <i class="fa fa-check"></i> </button>
                <button class="btn btn-sm btn-danger margin-5" data-dismiss="modal" aria-hidden="true"> 取消 <i class="fa fa-times"></i> </button>
            </div>
        </div>
    </div>
</div>
<script>
$(function() {
    $('[data-toggle="popover"]').popover({
        trigger: 'hover'
    });
    // $('.select2-search input').keypress(function(e) {
        // if(e.which == 13) {
        //     alert('hi~');
        // }
    // });

    var sendsearch = function(text){
        $('#selectproduct .tb-body-container').html('<div style="text-align:center;padding-top:140px;"><img src="/images/loading.gif"/>Loading...</div>');

        $.ajax({
            url:'/admin/product/search',
            method: 'post',
            data: {
                search: text,
                selected: selectResultArray,
                _token: LA.token
            },
            success: function (result) {
                if(result){
                    $('#selectproduct .tb-body-container').html(result); 
                }else{
                    text = (text == 'searchselected') ? '已選擇' : ' 【 ' + text +' 】 ';
                    $('#selectproduct .tb-body-container').html('<div style="text-align:center;padding-top:140px;">查無' + text + '資料</div>'); 
                }
            }
        });
        $('#searchtext').focus();
    }

    $('#searchtext')
    .focus(function(){this.select();})
    .on('keyup', function (e) {
        if(e.keyCode == 13) {
            sendsearch($(this).val());
        }
    });

    $('#searchbutton').on('click', function () {
        sendsearch($('#searchtext').val());
    });
    
    $('#searchall').on('click', function () {
        $('#searchtext').val('');
        sendsearch('searchall');
    });
    $('#searchselected').on('click', function () {
        $('#searchtext').val('');
        sendsearch('searchselected');
    });

    $('#addsubmit').on('click', function () {
        if(selectResultArray.length > 0){
            var firsttime = true;
            var rowTop;
            if($('#receiptdetials').length > 0){
                firsttime = false;
                rowTop = $("#receiptdetials .tb-body-container").find("div[role='row']").last().css("top");
                
            }
            $.ajax({
                url:'/admin/product/receiptdetails',
                method: 'post',
                data: {
                    selected: selectResultArray,
                    firsttime: firsttime,
                    rowTop: rowTop,
                    _token: LA.token
                },
                success: function (result) {
                    if(result){
                        if(firsttime){
                            $('.form-horizontal .box-body').append(result);  
                        }else{
                            $('#receiptdetials .tb-body-container').append(result); 
                        } 
                        $('#selectproduct').modal('hide');
                    }
                }
            });
        }else{
            alert('未選擇商品');
        }
    });

    

});
</script>
@endif