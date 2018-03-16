var selectResultArray = [];

/** 秀出商品LIST */
function ShowModal(target) {
    var url = '/admin/modal/' + target;

    $.get(url, function (data) {
        $("body").append(data);
        $('#selectproduct')
            .modal('show')
            .on('hidden.bs.modal', function (e) {
                $(this).remove();
                selectResultArray = [];
            });
    });
}

/** 展開進貨單明細 */
function ShowReceiptDetails(reid) {
    var url = '/admin/product/receiptdetails/' + reid; //$("input[name='reid']").val();

    $.get(url, function (data) {
        $('.form-horizontal .box-body:first').append(data);
    });
}

/** 展開調撥單明細 */
function ShowTransferDetails(tid) {
    var url = '/admin/transfer/transferdetails/' + tid; //$("input[name='tid']").val();

    $.get(url, function (data) {
        $('.form-horizontal .box-body:first').append(data);
    });
}

/** 展開配貨單明細 */
function ShowSalesAssignDetails(said) {
    var url = '/admin/sales/assigndetails/' + said; //$("input[name='tid']").val();

    $.get(url, function (data) {
        $('.form-horizontal .box-body:first').append(data);
    });
}

/** 展開領貨單明細 */
function ShowSalesCollectDetails(scid) {
    var url = '/admin/sales/collectdetails/' + scid; //$("input[name='tid']").val();

    $.get(url, function (data) {
        $('.form-horizontal .box-body:first').append(data);
    });
}

/** 展開退貨單明細 */
function ShowSalesRefundDetails(srid) {
    var url = '/admin/sales/refunddetails/' + srid; //$("input[name='tid']").val();

    $.get(url, function (data) {
        $('.form-horizontal .box-body:first').append(data);
    });
}

/** 避免javascript的加法BUG */
function accAdd(arg1, arg2) {
    var r1, r2, m, c;
    try { r1 = arg1.toString().split(".")[1].length } catch (e) { r1 = 0 }
    try { r2 = arg2.toString().split(".")[1].length } catch (e) { r2 = 0 }
    c = Math.abs(r1 - r2);
    m = Math.pow(10, Math.max(r1, r2))
    if (c > 0) {
        var cm = Math.pow(10, c);
        if (r1 > r2) {
            arg1 = Number(arg1.toString().replace(".", ""));
            arg2 = Number(arg2.toString().replace(".", "")) * cm;
        }
        else {
            arg1 = Number(arg1.toString().replace(".", "")) * cm;
            arg2 = Number(arg2.toString().replace(".", ""));
        }
    }
    else {
        arg1 = Number(arg1.toString().replace(".", ""));
        arg2 = Number(arg2.toString().replace(".", ""));
    }
    return (arg1 + arg2) / m
}

/** 避免javascript的乘法BUG */
function accMul(arg1, arg2) {
    var m = 0,
        s1 = arg1.toString(),
        s2 = arg2.toString();
    try {
        m += s1.split(".")[1].length;
    } catch (e) { }
    try {
        m += s2.split(".")[1].length;
    } catch (e) { }
    return Number(s1.replace(".", "")) * Number(s2.replace(".", "")) / Math.pow(10, m);
}
/** 避免javascript的除法BUG */
function accDiv(arg1, arg2) {
    var t1 = 0,
        t2 = 0,
        r1, r2;
    try {
        t1 = arg1.toString().split(".")[1].length;
    } catch (e) { }
    try {
        t2 = arg2.toString().split(".")[1].length;
    } catch (e) { }
    with (Math) {
        r1 = Number(arg1.toString().replace(".", ""));
        r2 = Number(arg2.toString().replace(".", ""));
        return (r1 / r2) * pow(10, t2 - t1);
    }
}

$(document).ajaxComplete(function () {
    $('[data-toggle="popover"]').popover({
        trigger: 'hover'
    });
});