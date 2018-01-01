function ShowModal(target) {
    // var url = '/admin/modal?t=' + target;
    var url = '/admin/modal/' + target;
    $.get(url, function(data) {
        $("body").append(data);
        $('#select' + target).modal('show').on('hidden.bs.modal', function(e) {
            $(this).remove();
        });
    });
    // var str = '<div class="modal fade" id="selectproduct">' +
    //     '<div class="modal-dialog modal-lg">' +
    //     '<div class="modal-content">' +
    //     '<div class="modal-header">' +
    //     '<button type="button" class="close" data-dismiss="modal" aria-hidden="true"> &times; </button>' +
    //     '<h3>查看</h3>' +
    //     '</div>' +
    //     '<div class="modal-body">彈出式視窗(網頁載入)</div>' +
    //     '<div class="modal-footer">' +
    //     '<button class="btn btn-default" data-dismiss="modal" aria-hidden="true">關閉</button>' +
    //     '</div>' +
    //     '</div>' +
    //     '</div>';

    // $("body").append(str);
    // $.get(url, function(data) {
    //     $("#viewmodal").find('.modal-body').html(data);
    // });


}