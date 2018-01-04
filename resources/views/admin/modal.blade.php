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
                        <div class="flex-col-xs no-padding padding-right-5">
                            <div class="input-group">
                                <input class="form-control no-border-right" placeholder="請輸入搜尋的商品名 或 商品編號" type="text">
                                <span class="input-group-btn">
                                <button class="btn btn-transparent-grey2" type="button"> <i class="fa fa-search"></i> </button>
                                </span>
                            </div>
                        </div>
                        <button class="btn btn-orange"> 新增<i class="fa fa-plus margin-left-5"></i> </button>
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
                                        <div class="tb-body-container" style="height: 720px; top: 0px; width: 837px;">
                                        
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>




                    </div>
                </div>
                <div class="select2-results">
                    <span>已選擇：25 </span>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-success margin-5"> 加入 <i class="fa fa-check"></i> </button>
                <button class="btn btn-danger margin-5" data-dismiss="modal" aria-hidden="true"> 取消 <i class="fa fa-times"></i> </button>
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


    var selectResultArray = [];

    $('.select2-search input').on('keyup', function (e) {
         if(e.keyCode == 13) {

            $('#selectproduct .tb-body-container').html('<span style="padding:10px;">Loadding...</span>');


            $.ajax({
                url:'/admin/product/search',
                method: 'post',
                data: {
                    search: $(this).val(),
                    _token: LA.token
                },
                success: function (result) {
                    $('#selectproduct .tb-body-container').html(result);
                }
            });
            
        }
    });
});
</script>