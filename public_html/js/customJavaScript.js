jQuery(document).ready(function($){
    $(".dropdown").mouseover(function () {
        $(this).addClass("open");
    });
    $(".dropdown").mouseout(function () {
        $(this).removeClass("open");
    });
});

 

