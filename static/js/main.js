$(function(){
    var _width = $(window).width();

    $('header span.menu-icon').click(function(){
        $('nav#nav-main').toggle('slow');
    });
    $( window ).on('resize', function() {
        if ( _width != $(this).width() ) {
            $('nav#nav-main').toggle($('header span.menu-icon').is(':hidden'));
                _width = $(this).width();
        }
    });
    $('div.task div.title').click(function(){
        $(this).parent().find('div.content').toggle('slow');
    });
    $('fieldset legend').click(function(){
        $(this).parent().find('table').toggle();
    });
});
