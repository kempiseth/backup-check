$(function(){
    var base_url = '/human-resource';

    $('table#select-staff').on('click','td.action img.icon',function(event){
        event.stopPropagation();

        var img_icon = $(this);
        var action = $(this).attr('action');
        var row = $(this).parent().parent();
        var staff_id = row.attr('staff_id');
        if (action=='update' || ($.inArray(action, ['disable', 'remove']) != -1) && confirm('តើ​អ្នក​ប្រាកដ​ឬ​អត់?')) {
            $.post({
                url: '',
                data: {_ajax: action+'Staff', staff_id: staff_id},
                success: function(result) {
                    if ($.trim(result)=='OK') {
                        if (action=='remove') {
                            row.remove();
                        } else if (action=='disable') {
                            row.find('td:first').attr('class','caution');
                            img_icon.attr('src','/static/image/delete-red.jpg');
                            img_icon.attr('action','remove');
                            img_icon.attr('title','Remove');
                        }
                    }
                },
            });
        }
    });
    $('table#select-staff').on('click','tr',function(){
        var staff_id = $(this).attr('staff_id');
        if (getSelection().toString() == '') {
            window.location.replace(base_url+'/detail?staff_id='+staff_id);
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
                            update_img= roles.update ?
                                '<img class="icon" action="update" src="/static/image/update.jpg" title="Edit">' : '';
                            delete_img= roles.delete ?
                                (value.is_active ?
                                    '<img class="icon" action="disable" src="/static/image/delete.jpg" title="Disable">' :
                                    '<img class="icon" action="remove" src="/static/image/delete-red.jpg" title="Remove">') : 
                                '';
                            var row = '<tr staff_id="'+value.id+
                                '"><td class="' + (value.is_active ? 'active' : 'caution') + '">'+value.name+
                                '</td><td>'+value.sex+
                                '</td><td>'+value.dob+
                                '</td><td>'+value.position+
                                '</td><td class="action">'+update_img+delete_img+'</td></tr>';
                            $('table#select-staff').append(row);
                        });
                    }
                },
            });
        }
    });
});
