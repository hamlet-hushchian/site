$(document).ready(function () {
    $('.slider__main').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        focusOnSelect: true,
        adaptiveHeight: true,
        prevArrow: $('.mainx-prev'),
        nextArrow: $('.mainx-next'),
        asNavFor: '.slider__preview'
    });

    $('.slider__preview').slick({
        slidesToShow: 5,
        slidesToScroll: 1,
        asNavFor: '.slider__main',
        focusOnSelect: true,
        arrows: true,
        vertical: true,
        prevArrow: $('.preview-up'),
        nextArrow: $('.preview-down')
    });

    function initMap() {
        var uluru = {lat: -25.363, lng: 131.044};
        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 4,
            center: uluru
        });
        var marker = new google.maps.Marker({
            position: uluru,
            map: map
        });
    }
})