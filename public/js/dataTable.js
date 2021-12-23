$(document).ready(function() {
    $('#table-contact').DataTable({
        language: {
            url: '/js/dataTable.french.json'
        },
        "aoColumnDefs": [
            { 'bSortable': false, 'aTargets': [ 4,6 ] }
        ]
    });
});

$(document).ready(function() {
    $('#table-article').DataTable({
        language: {
            url: '/js/dataTable.french.json'
        },
        "aoColumnDefs": [
            { 'bSortable': false, 'aTargets': [ 7,8,11 ] }
        ]
    });
});

$(document).ready(function() {
    $('#table-user').DataTable({
        language: {
            url: '/js/dataTable.french.json'
        },
        "aoColumnDefs": [
            { 'bSortable': false, 'aTargets': [ 11,12 ] }
        ]
    });
});

$(document).ready(function() {
    $('#table-comment').DataTable({
        language: {
            url: '/js/dataTable.french.json'
        },
        "aoColumnDefs": [
            { 'bSortable': false, 'aTargets': [ 1,3,4,5 ] }
        ]
    });
});


// $(function() {
//     // Sections height
//     $(window).resize(function() {
//         var sH = $(window).height();
//         $('.NomDeTaClass').css('height', sH + 'px');
//     });        
// });

