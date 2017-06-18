$(function(){
    var base_url = '/human-resource';

    $('td.action img.icon').click(function(event){
        event.stopPropagation();

        var action = $(this).attr('action');
    });
    $('table#select-staff tr').click(function(){
        var staff_id = $(this).attr('staff_id');
        if (getSelection().toString() == '') {
            window.location.href = base_url+'/detail?staff_id='+staff_id;
        }
    });
    $('input#staff-search').keyup(function(event){
        if ( event.which == 13 ) {
            var text = $(this).val();
            $.post({
                url: '',
                data: {_ajax: 'searchStaff', text: text},
                dataType: 'json',
                success: function(result) {
                    $('table#select-staff tr').remove();
                    if($.isEmptyObject(result)) {
                        $('table#select-staff').html('<tr><td><center class="caution">រកមិនឃើញ បុគ្គលិក</tcenter></td></tr>');
                    } else {
                        $.each(result, function( index, value ) {
                            var row = '<tr staff_id="'+value.id+
                                '"><td class="' + (value.is_active ? 'active' : 'caution') + '">'+value.name+
                                '</td><td>'+value.sex+
                                '</td><td>'+value.dob+
                                '</td><td>'+value.position+
                                '</td><td class="action">'+
                                    '<img class="icon" action="update" src="/static/image/update.jpg" title="Edit">'+
                                    '<img class="icon" action="delete" src="/static/image/delete.jpg" title="Remove">'+
                                '</td></tr>';
                            $('table#select-staff').append(row);
                        });
                    }
                },
            });
        }
    });
});
