var selectResultArray = [];

/** 秀出商品LIST */
function ShowModal(target) {
    // var url = '/admin/modal?t=' + target;
    var url = '/admin/modal/' + target;

    $.get(url, function(data) {
        $("body").append(data);
        $('#select' + target)
            .modal('show')
            .on('hidden.bs.modal', function(e) {
                $(this).remove();
                selectResultArray = [];
            });
    });
}

/** 展開進貨單明細 */
function ShowReceiptDetails() {
    var url = '/admin/product/receiptdetails/' + $("input[name='reid']").val();

    $.get(url, function(data) {
        $('.form-horizontal .box-body:first').append(data);
    });
}

/** 展開調撥單明細 */
function ShowTransferDetails() {
    var url = '/admin/product/transferdetails/' + $("input[name='tid']").val();

    $.get(url, function(data) {
        $('.form-horizontal .box-body:first').append(data);
    });
}

/** 避免javascript的乘法BUG */
function accMul(arg1, arg2) {
    var m = 0,
        s1 = arg1.toString(),
        s2 = arg2.toString();
    try {
        m += s1.split(".")[1].length;
    } catch (e) {}
    try {
        m += s2.split(".")[1].length;
    } catch (e) {}
    return Number(s1.replace(".", "")) * Number(s2.replace(".", "")) / Math.pow(10, m);
}
/** 避免javascript的除法BUG */
function accDiv(arg1, arg2) {
    var t1 = 0,
        t2 = 0,
        r1, r2;
    try {
        t1 = arg1.toString().split(".")[1].length;
    } catch (e) {}
    try {
        t2 = arg2.toString().split(".")[1].length;
    } catch (e) {}
    with(Math) {
        r1 = Number(arg1.toString().replace(".", ""));
        r2 = Number(arg2.toString().replace(".", ""));
        return (r1 / r2) * pow(10, t2 - t1);
    }
}