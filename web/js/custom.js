(function () {
    $(window).scroll(function () {
        var top = $(document).scrollTop();
        $('.splash').css({
            'background-position': '0px -' + (top / 3).toFixed(2) + 'px'
        });
        if (top > 50)
            $('#home > .navbar').removeClass('navbar-transparent');
        else
            $('#home > .navbar').addClass('navbar-transparent');
    });

    $("a[href='#']").click(function (e) {
        e.preventDefault();
    });


    $('.bs-component [data-toggle="popover"]').popover();
    $('.bs-component [data-toggle="tooltip"]').tooltip();

    $('#reload_actions').click(function () {
        // TODO action ajax
        $('.alert-success').show();
    });

    $(document).ready(function () {
        $('#table_vendor').DataTable({
            "language": {
                url: ''
            },
            "order": [[5, "desc"]],
            "processing": true,
            "serverSide": true,
            "ajax": "log-vendors-ajax",
            "createdRow": function (row, data, index) {
                if (data.statusWalletAccount.status == 2 || data.statusWalletAccount.status == 4) {
                    $('td', row).eq(3).addClass('danger');
                }else if(data.statusWalletAccount.status == 1 || data.statusWalletAccount.status == 3){
                    $('td', row).eq(3).addClass('success');
                }
                if (data.status.status == 3 || data.status.status == 4) {
                    $('td', row).eq(2).addClass('danger');
                } else if (data.status.status == 1 || data.status.status == 3) {
                    $('td', row).eq(2).addClass('success');
                }
            },
            "drawCallback" : function () {
                $('.vendor-notice').popover();
            },
            "columns": [
                {"data": "miraklId"},
                {"data": "login"},
                {
                    "data": "status",
                    "render": function (data) {
                        return data.label+" "+data.button;
                        
                    }
                },
                {
                    "data": "statusWalletAccount",
                    "render": function (data) {
                        return data.label;
                    }
                },
                {"data": "hipayId"},
                {"data": "date"},
                {
                    "data": "document",
                    "render": function (data) {
                        return ' <a href="#" onclick="popup_vendor_detail(' + data.miraklId + ');"> Voir le detail</a>'
                    }
                }
            ]
        });
        $('#table_transferts').DataTable({
            "language": {
                url: ''
            },
            "processing": true,
            "serverSide": true,
            "ajax": "log-operations-ajax",
            "createdRow": function (row, data, index) {
                if (data.statusWithDrawal.status == -7 || data.statusWithDrawal.status == -8) {
                    $('td', row).eq(5).addClass('danger');
                }else{
                    $('td', row).eq(5).addClass('success');
                }
                if (data.statusTransferts.status == -9) {
                    $('td', row).eq(4).addClass('danger');
                } else {
                    $('td', row).eq(4).addClass('success');
                }
            },
            "drawCallback" : function () {
                $('.vendor-notice').popover();
            },
            "columns": [
                {"data": "miraklId"},
                {"data": "hipayId"},
                {
                    "data": "paymentVoucher",
                },
                {"data": "amount"},
                {
                    "data": "statusTransferts",
                    "render": function (data) {
                        return data.label +" "+data.button;
                    }
                },
                {
                    "data": "statusWithDrawal",
                    "render": function (data) {
                        return data.label +" "+data.button;
                    }
                },
                {
                    "data": "balance",
                }
            ]
        });
        $('#table_logs').DataTable({
            "language": {
                url: ''
            },
            "order": [[0, "desc"]],
            "processing": true,
            "serverSide": true,
            "ajax": "log-general-ajax",
            "columns": [
                {"data": "createdAt"},
                {"data": "levelName"},
                {"data": "action"},
                {"data": "miraklId"},
                {"data": "message"}
            ]
        });
    });
})();


