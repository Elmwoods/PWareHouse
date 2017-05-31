var li = $('.nav_right ul li a');
    li.each(function(){
        
            if($($(this))[0].href==String(window.location)){
                $(this).removeClass('red');
                $(this).addClass('ccc');
            }
    });