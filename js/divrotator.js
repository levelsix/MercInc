$(document).ready(function() { 

    $(".goleft").click( function(e) {
        var $active = $(".active");
        var $next = $active.prev().length ? $active.prev() : $(".focusitem:last");
    
        $active.removeClass('active');
        $active.addClass('notactive');
        $next.addClass('active');
        $next.removeClass('notactive');
    });

    $(".goright").click( function(e) {
        var $active = $(".active");
        var $next = $active.next().length ? $active.next() : $(".focusitem:first");
     
        $active.removeClass('active');
        $active.addClass('notactive');
        $next.addClass('active');
        $next.removeClass('notactive');
    }); 

});
