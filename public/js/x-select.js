$(document).ready(function () {
    if (!Array.prototype.indexOf) {
        Array.prototype.indexOf = function (val) {
            var i = this.length;
            while (i--) {
                if (this[i] == val) return i;
            }
            return -1;
        }
    }

    s_link();

    $(document).click(function(){
        $('.adv_select-tt').closest('.x-select-item-wrap').removeClass('opened');
        $('.x-select-tt').closest('.x-select-item-wrap').removeClass('opened');
    });
    $(".adv_select, .adv_select-tt, .x-select, .x-select-tt").click(function(e) {
        e.stopPropagation();
        return false;
    });


    $('.x-select').click(function () {
        $('.x-select').not(this).parent('div').removeClass('opened');
        $(this).parent('div').toggleClass('opened');
    });

    $('.x-select-tt-item').hover(function () {
        $(this).closest('.x-select-tt').find('.x-select-tt-item').removeClass('hovered');
        $(this).addClass('hovered');
    });

    var rooms = [];
    $('.x-select-tt-item').click(function () {
        var choise = $(this).text();
        var choise_val = $(this).data("value");
        if (!$(this).closest('.x-select-item-wrap').hasClass('checker')) {
            $(this).closest('.x-select-item-wrap').find('.x-select').text(choise);
            $(this).closest('.x-select-item-wrap').find('.x-select').attr("value",choise_val);
            $(this).closest('.x-select-tt').find('.x-select-tt-item').removeClass('checked');
            $(this).addClass('checked');
            $(this).closest('.x-select-item-wrap').removeClass('opened');
        }
        else {
            var room = $(this).data('room');
            if (!$(this).hasClass('checked')) {
                $(this).addClass('checked');
                rooms.push(room);
            }
            else {
                $(this).removeClass('checked');
                var index = rooms.indexOf(room);
                if (index > -1) {
                    rooms.splice(index, 1);
                }
            }
            rooms.sort();
            var str = '';
            var val = 0;
            if (rooms.length == 0) {
                str = 'Комнат';

            }
            else {
                var val = rooms.join();
                for (i = 0; i < rooms.length; i++) {
                    if (i == 0) {
                        str += rooms[i];
                    }
                    else {
                        str += ',' + rooms[i];
                    }
                }
                if (rooms.length == 1) {
                    str += '-комнатная';
                }
                else if (rooms.length < 3) {
                    str += ' комн.';
                }
                else {
                    str += ' к.';
                }

            }
            $(this).closest('.x-select-item-wrap').find('.x-select').text(str);
            $(this).closest('.x-select-item-wrap').find('.x-select').attr("value",val);
        }
        s_link();
    });
    var currencies = ['UAH','USD','EUR'];
    $("#swiper").click(function () {
        var e = $(this).children('div');
        var cur_val = e.text();
        var index = (currencies.indexOf(cur_val) + 1 < 3 ? currencies.indexOf(cur_val) + 1 : 0);
        e.text(currencies[index]);
        s_link();
    })




    //adv_select
    $('.adv_select').click(function(){
        // $('.adv_select').not(this).siblings('div').slideUp();
        $(this).siblings('div').slideToggle(function(){
            $(this).children('div').children('.adv_from').focus();
        });
    });
    function adv_replace_text(from, to, elem){
        var adv_select_str = "";
        if(to == ""){
            adv_select_str = "От "+from;
        }else if(from == ""){
            adv_select_str = "До "+to;
        }else{
            adv_select_str = from+" - "+to;
        }
        elem.html(adv_select_str);
    }
    $('.adv_select-tt>ul>li').click(function(e){
        var adv_select_val = $(this).html();
        if(!$(this).hasClass('text-right')){
            $(this).parent('ul').siblings('div').children('.adv_from').val(adv_select_val);
            s_link();
            var from = adv_select_val;
            var to = $(this).parent('ul').siblings('div').children('.adv_to').val();
            // var elem = $(this).parent('ul').parent('.adv_select-tt').siblings('.adv_select');
            var elem = $(this).closest('.x-select-item-wrap').find('.x-select');
            adv_replace_text(from, to, elem);
            $(this).parent('ul').siblings('div').children('.adv_to').focus();
            $(this).parent('ul').children('li').addClass('text-right');
            e.stopPropagation();
            return false;
        }else{
            $(this).parent('ul').siblings('div').children('.adv_to').val(adv_select_val);
            s_link();
            var from = $(this).parent('ul').siblings('div').children('.adv_from').val();
            var to = adv_select_val;
            // var elem = $(this).parent('ul').parent('.adv_select-tt').siblings('.adv_select');
            var elem = $(this).closest('.x-select-item-wrap').find('.x-select');
            adv_replace_text(from, to, elem);
            // $(this).parent('ul').parent('div').slideUp();
            $(this).closest('.x-select-item-wrap').removeClass('opened');
        }
    });
    $(".adv_from").change(function(){
        var from = $(this).val();
        var to = $(this).siblings('.adv_to').val();
        // var elem = $(this).parent('.adv_select_inputs').parent('.adv_select-tt').siblings('.adv_select');
        var elem = $(this).closest('.x-select-item-wrap').find('.x-select');
        adv_replace_text(from, to, elem);
        s_link();
    });
    $(".adv_to").change(function(){
        var from = $(this).siblings('.adv_from').val();
        var to = $(this).val();
        // var elem = $(this).parent('.adv_select_inputs').parent('.adv_select-tt').siblings('.adv_select');
        var elem = $(this).closest('.x-select-item-wrap').find('.x-select');
        adv_replace_text(from, to, elem);
        // $(this).parent('div').parent('div').slideUp();
        $(this).closest('.x-select-item-wrap').removeClass('opened');
        s_link();
    });
    $('.adv_from').focus(function(){
        $(this).parent('div').siblings('ul').children('li').removeClass('text-right');
        var limit = parseInt($(this).siblings('.adv_to').val());
        if(limit !== ""){
            $(this).parent('div').siblings('ul').children('li').each(function(){
                $(this).show();
                if(parseInt($(this).html()) > limit){
                    $(this).hide();
                }
            });
        }

    });
    $('.adv_to').focus(function(){
        $(this).parent('div').siblings('ul').children('li').addClass('text-right');
        var limit = parseInt($(this).siblings('.adv_from').val());
        if(limit !== ""){
            $(this).parent('div').siblings('ul').children('li').each(function(){
                $(this).show();
                if(parseInt($(this).html()) < limit){
                    $(this).hide();
                }
            });
        }
    });


});


function s_link() {
    var link = '';
    var d_type = $($('.x-select')[0]).attr('value');
    var p_type = $($('.x-select')[1]).attr('value');
    var city = $($('.x-select')[2]).attr('value');
    var rooms = $($('.x-select')[3]).attr('value').split(',');
    var p_from = $("#price_from").val().replace(' ','');
    var p_to = $("#price_to").val().replace(' ','');
    var cur = $("#swiper div").text().toLowerCase();

    link = '/'+d_type+'-'+p_type+'-'+city;


    if(rooms.length > 0 && rooms[0] !== "0")
    {
        $.each(rooms,function () {
            if(!hasGet(link))
            {
                link += '?rooms='+this;
            }
            else
            {
                link += '&rooms='+this;
            }
        })
    }
    if(p_from !== '')
    {
        if(!hasGet(link))
        {
            link += '?price_from='+p_from;
        }
        else
        {
            link += '&price_from='+p_from;
        }
    }
    if(p_to !== '')
    {
        if(!hasGet(link))
        {
            link += '?price_to='+p_to;
        }
        else
        {
            link += '&price_to='+p_to;
        }
    }
    if(p_from !== '' || p_to !== '')
    {
        link += '&currency='+cur;
    }
    $('a.s_link').attr('href', link);
}
function hasGet(link) {
    var re = /\?/;
    return re.test(link);
}