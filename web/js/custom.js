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
                }else if(data.status.status == 1 || data.status.status == 2){
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
                        return data.nb+' <a href="#" onclick="popup_vendor_detail(' + data.miraklId + ');"> Voir le detail</a>'
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
            "columns": [
                {"data": "miraklId"},
                {"data": "hipayId"},
                {
                    "data": "dateCreated",
                },
                {
                    "data": "paymentVoucher",
                },
                {"data": "amount"},
                {"data": "statusTransferts"},
                {
                    "data": "statusWithDrawal",
                },
                {
                    "data": "balance",
                }
            ]
        });
        $('#table_logs').DataTable({
            "language": {
                "sProcessing": "Traitement en cours...",
                "sSearch": "Rechercher&nbsp;:",
                "sLengthMenu": "Afficher _MENU_ &eacute;l&eacute;ments",
                "sInfo": "Affichage de l'&eacute;lement _START_ &agrave; _END_ sur _TOTAL_ &eacute;l&eacute;ments",
                "sInfoEmpty": "Affichage de l'&eacute;lement 0 &agrave; 0 sur 0 &eacute;l&eacute;ments",
                "sInfoFiltered": "(filtr&eacute; de _MAX_ &eacute;l&eacute;ments au total)",
                "sInfoPostFix": "",
                "sLoadingRecords": "Chargement en cours...",
                "sZeroRecords": "Aucun &eacute;l&eacute;ment &agrave; afficher",
                "sEmptyTable": "Aucune donn&eacute;e disponible dans le tableau",
                "oPaginate": {
                    "sFirst": "Premier",
                    "sPrevious": "Pr&eacute;c&eacute;dent",
                    "sNext": "Suivant",
                    "sLast": "Dernier"
                },
                "oAria": {
                    "sSortAscending": ": activer pour trier la colonne par ordre croissant",
                    "sSortDescending": ": activer pour trier la colonne par ordre d&eacute;croissant"
                }
            }
        });
    });
})();


