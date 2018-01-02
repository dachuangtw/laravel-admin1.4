function ShowModal(target) {
    // var url = '/admin/modal?t=' + target;
    var url = '/admin/modal/' + target;

    $.get(url, function(data) {
        $("body").append(data);
        $('#select' + target).modal('show').on('hidden.bs.modal', function(e) {
            $(this).remove();
        });
    });
}