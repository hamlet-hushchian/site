$(document).ready(function () {
    $(".toggle_mnu").click(function() {
        $(".sandwich").toggleClass("active");
        $(".top-line nav").slideToggle(10);
    });

    $('.filter_wrap a').click(function (e) {
        e.preventDefault();
    });
    
});//End of document ready