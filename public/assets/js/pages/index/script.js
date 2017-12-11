$(document).ready(function () {
    s_link();

    $('.x-select-tt-item').click(function () {
        s_link();
    });

    $("#swiper").click(function (){
        s_link();
    });

    $('.adv_select-tt>ul>li').click(function()
    {
        s_link();
    });

    $(".adv_from,.adv_to").change(function()
    {

    });

    var mixer = mixitup('#mixer1', {
        load: {
            filter: '.s1'
        },
        selectors: {
            control: '.f1'
        }
    });

    var mixer2 = mixitup('#mixer2', {
        load: {
            filter: '.sr1'
        },
        selectors: {
            control: '.f2'
        }
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


        if(rooms.length > 0 && rooms[0] !== "null")
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

});//End of document ready