$(document).ready(function() {
    $('#table-contact').DataTable({
        language: {
            url: '/js/dataTables.french.json'
        },
        "aoColumnDefs": [
            { 'bSortable': false, 'aTargets': [ 4 ] }
        ]
    });
});
