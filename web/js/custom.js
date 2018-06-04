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
        if ($('#table_vendor').length) {
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
                                "date-end": $("#end").val(),
                                "country": $("#country").val()
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

                    if (data.enabled.enabled == false ) {
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
                    {
                        "data": "enabled",
                        "render": function (data) {
                            return data.label;

                        }
                    },
                    {"data": "hipayId"},
                    {"data": "country"},
                    {"data": "date"},
                    {
                        "data": "document",
                        "render": function (data) {
                            return data.button;
                        },
                        "sortable": false
                    }
                ]
            });

            $("#filter-action").click(function () {
                vendorTable.draw();
            });
        }
        if ($('#table_transfers').length) {

            var operationTable = $('#table_transfers').DataTable({
                "language": {
                    url: 'datatable/locale'
                },
                "processing": true,
                "serverSide": true,
                "order": [[8, "desc"]],
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
                    if (data.statusWithDrawal.status == -7 || data.statusWithDrawal.status == -8 || data.statusWithDrawal.status == -11 || data.statusWithDrawal.status == -6) {
                        $('td', row).eq(6).addClass('danger');
                    } else if (data.statusWithDrawal.status == 6 || data.statusWithDrawal.status == 5 || data.statusWithDrawal.status == 2) {
                        $('td', row).eq(6).addClass('success');
                    }
                    if (data.statusTransferts.status == -9  || data.statusTransferts.status == -10 || data.statusTransferts.status == -5) {
                        $('td', row).eq(5).addClass('danger');
                    } else if (data.statusTransferts.status == 3 || data.statusTransferts.status == 2) {
                        $('td', row).eq(5).addClass('success');
                    }else if (data.statusTransferts.status == -1) {
                        $('td', row).eq(5).addClass('info');
                        $('td', row).eq(6).addClass('info');
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
                    {"data": "originAmount"},
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
                    },
                    {
                        "data": "dateCreated",
                    }
                ]
            });

            $("#filter-action").click(function () {
                operationTable.draw();
            });
        }

        if ($('#table_logs').length) {
            var logTable = $('#table_logs').DataTable({
                "language": {
                    url: 'datatable/locale'
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

            $("#filter-action").click(function () {
                logTable.draw();
            });

        }

        if ($('#table_batchs').length) {

            var batchTable = $('#table_batchs').DataTable({
                "language": {
                    url: 'datatable/locale'
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

            setInterval(function () {
                batchTable.draw();
            }, 5000);

        }
    });
})();

