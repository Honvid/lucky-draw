/**
 * Theme: Ubold Admin Template
 * Author: Coderthemes
 * bootstrap tables
 */



$(document).ready(function () {


    // BOOTSTRAP TABLE - CUSTOM TOOLBAR
    // =================================================================
    // Require Bootstrap Table
    // http://bootstrap-table.wenzhixin.net.cn/
    // =================================================================
    var $table = $('#demo-custom-toolbar'), $remove = $('#demo-delete-row');


    $table.on('check.bs.table uncheck.bs.table check-all.bs.table uncheck-all.bs.table', function () {
        $remove.prop('disabled', !$table.bootstrapTable('getSelections').length);
    });

    $remove.click(function () {
        var ids = $.map($table.bootstrapTable('getSelections'), function (row) {
            return row.id
        });
        $table.bootstrapTable('remove', {
            field: 'id',
            values: ids
        });
        $remove.prop('disabled', true);
    });


});

/**
 * Hack for table loading issue - ideally this should be fixed in plugin code itself.
 */
$(window).load(function() {
   $('[data-toggle="table"]').show();
});



