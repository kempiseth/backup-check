$(function(){
    var base_url = '/human-resource';

    $('td.action img.icon').click(function(event){
        event.stopPropagation();

        var action = $(this).attr('action');
    });
    $('table#select-staff tr').click(function(){
        var staffid = $(this).attr('staffid');
        window.location.href = base_url+'/detail?staffid='+staffid;
    });
});
