$(function(){
    var base_url = '/human-resource';

    $('td.action img.icon').click(function(event){
        event.stopPropagation();

        var action = $(this).attr('action');
    });
    $('table#select-staff tr').click(function(){
        var staff_id = $(this).attr('staff_id');
        window.location.href = base_url+'/detail?staff_id='+staff_id;
    });
});
