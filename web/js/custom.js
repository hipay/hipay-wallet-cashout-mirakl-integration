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
        var vendorTable = $('#table_vendor').DataTable({
            "language": {
                url: 'datatable/locale'
            },
            "order": [[5, "desc"]],
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "log-vendors-ajax",
                "data":
                        function (d) {
                            return $.extend({}, d, {
                                "status": $("#status-filter").val(),
                                "wallet-status": $("#wallet-status-filter").val(),
                                "date-start": $("#start").val(),
                                "date-end": $("#end").val()
                            });
                        }
            },
            "createdRow": function (row, data, index) {
                if (data.statusWalletAccount.status == 2 || data.statusWalletAccount.status == 4) {
                    $('td', row).eq(3).addClass('danger');
                } else if (data.statusWalletAccount.status == 1 || data.statusWalletAccount.status == 3) {
                    $('td', row).eq(3).addClass('success');
                }
                if (data.status.status == 3 || data.status.status == 4) {
                    $('td', row).eq(2).addClass('danger');
                } else if (data.status.status == 1 || data.status.status == 3) {
                    $('td', row).eq(2).addClass('success');
                }
            },
            "drawCallback": function () {
                $('.vendor-notice').popover();
            },
            "columns": [
                {"data": "miraklId"},
                {"data": "login"},
                {
                    "data": "status",
                    "render": function (data) {
                        return data.label + " " + data.button;

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

        var operationTable = $('#table_transferts').DataTable({
            "language": {
                url: ''
            },
            "processing": true,
            "serverSide": true,
            "order": [[2, "desc"]],
            "ajax": {
                "url": "log-operations-ajax",
                "data":
                        function (d) {
                            return $.extend({}, d, {
                                "status-transfer": $("#status-transfer").val(),
                                "status-withdraw": $("#status-withdraw").val()
                            });
                        }
            },
            "createdRow": function (row, data, index) {
                if (data.statusWithDrawal.status == -7 || data.statusWithDrawal.status == -8) {
                    $('td', row).eq(5).addClass('danger');
                } else {
                    $('td', row).eq(5).addClass('success');
                }
                if (data.statusTransferts.status == -9) {
                    $('td', row).eq(4).addClass('danger');
                } else {
                    $('td', row).eq(4).addClass('success');
                }
            },
            "drawCallback": function () {
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
                        return data.label + " " + data.button;
                    }
                },
                {
                    "data": "statusWithDrawal",
                    "render": function (data) {
                        return data.label + " " + data.button;
                    }
                },
                {
                    "data": "balance",
                }
            ]
        });
        var logTable = $('#table_logs').DataTable({
            "language": {
                url: ''
            },
            "order": [[0, "desc"]],
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "log-general-ajax",
                "data":
                        function (d) {
                            return $.extend({}, d, {
                                "log-level": $("#log-level").val(),
                                "date-start": $("#start").val(),
                                "date-end": $("#end").val()
                            });
                        }
            },
            "columns": [
                {"data": "createdAt"},
                {"data": "levelName"},
                {"data": "action"},
                {"data": "miraklId"},
                {"data": "message"}
            ]
        });

        var batchTable = $('#table_batchs').DataTable({
            "language": {
                url: ''
            },
            "order": [[0, "desc"]],
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "log-batch-ajax"
            },
            "drawCallback": function () {
                $('.vendor-notice').popover();
            },
            "createdRow": function (row, data, index) {
                if (data.state.state == -1) {
                    $('td', row).eq(2).addClass('danger');
                } else if (data.state.state == 1) {
                    $('td', row).eq(2).addClass('info');
                } else {
                    $('td', row).eq(2).addClass('success');
                }
            },
            "columnDefs": [{
                    "targets": 2,
                    "orderable": false
                }],
            "columns": [
                {"data": "startedAt"},
                {"data": "name"},
                {
                    "data": "state",
                    "render"
                            : function (data) {
                                return data.label + " " + data.button;
                            }
                }
            ]
        });

        $("#filter-action").click(function () {
            vendorTable.draw();
            operationTable.draw();
            logTable.draw();
        });
    });
})();


