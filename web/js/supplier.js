$(document).ready(function () {
    $('input[name=selection_all]').on('click', function (e) {
        let messageBox = $('#supplier-message-box');
        if ($(this).prop('checked')) {
            let selectedCount = $("input[name='selection[]']").length;
            let message = `All ${selectedCount} records on this page have been selected. <a href="javascript:" ` +
                `class="select_all_records">Select all records match this search.</a>`;
            messageBox.removeClass('alert-primary')
                .addClass('alert-danger')
                .css('display', 'block')
                .html(message);
        } else {
            messageBox.css('display', 'none')
                .html('');
        }
    });

    $('.supplier-index').on('click', '.select_all_records', function () {
        let messageBox = $('#supplier-message-box');
        let message = `All records in this search have been selected. <a href="javascript:" ` +
            `class="clear_select_all_records">Clear selection.</a>`;
        messageBox.removeClass('alert-danger')
            .addClass('alert-primary')
            .css('display', 'block')
            .html(message);
        $('input[name=select_all_records]').val(1);
    }).on('click', '.clear_select_all_records', function () {
        let messageBox = $('#supplier-message-box');
        let selectedCount = $("#w0").yiiGridView('getSelectedRows').length;
        let message = `All ${selectedCount} records on this page have been selected. <a href="javascript:" ` +
            `class="select_all_records">Select all records match this search.</a>`;
        messageBox.removeClass('alert-primary')
            .addClass('alert-danger')
            .css('display', 'block')
            .html(message);
        $('input[name=select_all_records]').val(0);
    }).on('click', '.supplier-export', function () {
        let selectAllRecord = $('input[name=select_all_records]').val();
        let searchParams = {};
        if (selectAllRecord === '1') {
            searchParams.id_compare_op = $("select[name='SupplierSearch[id_compare_op]']").val();
            searchParams.id_compare_number = $("input[name='SupplierSearch[id_compare_number]']").val();
            searchParams.name = $("input[name='SupplierSearch[name]']").val();
            searchParams.code = $("input[name='SupplierSearch[code]']").val();
            searchParams.t_status = $("input[name='SupplierSearch[t_status]']").val();
        } else {
            let selectedIds = $("#w0").yiiGridView('getSelectedRows');
            if (selectedIds.length === 0) {
                alert('Please select some records first.');
                return;
            }
            searchParams.selected_ids = selectedIds.join(',');
        }

        $.ajax({
            type: 'POST',
            data: searchParams,
            url: '/index.php?r=supplier/export-to-csv',
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                alert('Download failed.');
            },
            success: function (data, status, xhr) {
                let blob = new Blob([data]);
                let link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                let filename = '';
                let disposition = xhr.getResponseHeader('Content-Disposition');
                if (disposition && disposition.indexOf('attachment') !== -1) {
                    let filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
                    let matches = filenameRegex.exec(disposition);
                    if (matches != null && matches[1]) filename = matches[1].replace(/['"]/g, '');
                }
                if (filename === '') {
                    filename = 'SupplierExport.csv';
                }
                link.download = filename;
                link.click();
            }
        });
    });
})