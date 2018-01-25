<div class='modal fade' id="selectproduct">
    <input type="hidden" id="target" value="{{ $target }}">
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
                                            <div class="tb-header-cell" style="width: 80px; left: 543px;">總庫存</div>
                                            <div class="tb-header-cell" style="width: 200px; left: 623px;"></div>
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

    var sendsearch = function(text){
        $('#selectproduct .tb-body-container').html('<div style="text-align:center;padding-top:140px;"><img src="/images/loading.gif"/>Loading...</div>');
        var url = '';
        if($('#target').val() == 'product'){
            url = '/admin/product/search';
        }else if($('#target').val() == 'hasstock'){
            url = '/admin/product/searchstock';
        }else{
            alert("ERROR");
            return false;
        }

        $.ajax({
            url: url,
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

            //從網址獲知目前動作並設定只能在create或edit頁面使用 

            var firsttime = inputtext = true;
            var rowTop, action;
            var str = window.location.href;
            action = str.slice(str.lastIndexOf("/")+1); 
            if(action != 'create' && action != 'edit'){
                alert('網頁出了問題，請通知相關人員處理');
                return false;
            }

            if($('#target').val() == 'hasstock'){
                inputtext = false;
            }

            if($('#productdetails').length > 0){
                firsttime = false;
                rowTop = $("#productdetails .tb-body-container").find("div[role='row']").last().css("top");
            }
            $.ajax({
                url:'/admin/product/receiptdetails',
                method: 'post',
                data: {
                    action: action,
                    target: $('#target').val(),
                    inputtext: inputtext,
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
                            $('#productdetails .tb-body-container').append(result); 
                        } 
                        $('#selectproduct').modal('hide');
                    }
                },error: function (jqXHR, exception) {
                    var msg = '';
                    if (jqXHR.status === 0) {
                        msg = 'Not connect.\n Verify Network.';
                    } else if (jqXHR.status == 404) {
                        msg = 'Requested page not found. [404]';
                    } else if (jqXHR.status == 500) {
                        msg = 'Internal Server Error [500].';
                    } else if (exception === 'parsererror') {
                        msg = 'Requested JSON parse failed.';
                    } else if (exception === 'timeout') {
                        msg = 'Time out error.';
                    } else if (exception === 'abort') {
                        msg = 'Ajax request aborted.';
                    } else {
                        msg = 'Uncaught Error.\n' + jqXHR.responseText;
                    }
                    alert(msg + '\n\n網頁出了問題，請通知相關人員處理');
                },
            });
        }else{
            alert('未選擇商品');
        }
    });    

});
</script>