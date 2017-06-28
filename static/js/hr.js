$(function(){
    var base_url = '/human-resource';

    $('button#add-new').click(function(event){
        event.stopPropagation();

        var form = $("form[name='staff-insert-form']");
        var table = form.find('table');
        table.show();
        table.find('input[type=text]').filter(':first').focus();
    });
    $('table#select-staff').on('click','td.action img.icon',function(event){
        event.stopPropagation();

        var img_icon = $(this);
        var action = $(this).attr('action');
        var row = $(this).parent().parent();
        var staff_id = row.attr('staff_id');
        if ($.inArray(action, ['disable', 'remove']) != -1 && confirm('តើ​អ្នក​ប្រាកដ​ឬ​អត់?')) {
            $.post({
                url: '',
                data: {_ajax: action+'Staff', staff_id: staff_id},
                success: function(result) {
                    if ($.trim(result)=='OK') {
                        if (action=='remove') {
                            row.remove();
                        } else if (action=='disable') {
                            row.find('td:nth-child(2)').attr('class','caution');
                            img_icon.attr({
                                src: '/static/image/delete-red.jpg',
                                action: 'remove',
                                title: 'Remove'});
                        }
                    }
                },
            });
        } else if (action=='update') {
            window.location.replace(base_url+'/edit?staff_id='+staff_id);
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
                            var row = '<tr staff_id="'+value.id+'"><td>'+('000'+value.id).slice(-4)+
                                '</td><td class="' + (value.is_active ? 'active' : 'caution') + '">'+value.name+
                                '</td><td>'+value.sex+'</td><td>'+value.dob+'</td><td>'+value.position+
                                '</td><td class="action">'+update_img+delete_img+'</td></tr>';
                            $('table#select-staff').append(row);
                        });
                    }
                },
            });
        }
    });
    $('input#photo').change(function(){
        var MAX_WIDTH = 160;
        var MAX_HEIGHT = 180;

        if (this.files && this.files[0]) {
            var f = this.files[0];
            var reader = new FileReader();
            var wrapper = $(this).parent();

            reader.onload = function(e) {
                if( ! wrapper.find('img').length ) wrapper.prepend('<img style="visibility:hidden">');
                var img = wrapper.find('img')[0];

                img.onload = function() {
                    if( ! wrapper.find('canvas').length ) wrapper.prepend('<canvas></canvas>');
                    var canvas = wrapper.find('canvas')[0];
                    var width = img.width;
                    var height = img.height;

                    //if (width > height) {
                      if (width > MAX_WIDTH) {
                        height *= MAX_WIDTH / width;
                        width = MAX_WIDTH;
                      }
                    //} else {
                      if (height > MAX_HEIGHT) {
                        width *= MAX_HEIGHT / height;
                        height = MAX_HEIGHT;
                      }
                    //}

                    canvas.width = width;
                    canvas.height = height;
                    var ctx = canvas.getContext("2d");
                    ctx.drawImage(img, 0, 0, width, height);

                    wrapper.find('input.image-data').val(canvas.toDataURL());
                    wrapper.css('border','none');
                    $(img).remove(); canvas.scrollIntoView();
                }
                img.src = e.target.result;
            }
            reader.readAsDataURL(f);
        }
    });
});
